<?php
/**
 * Created by PhpStorm.
 * User: jacson Santos
 * Date: 21/01/2017
 * Time: 01:57
 */
namespace JSantos\ArangoModel;

use Silex\Application;
use triagens\ArangoDb\Collection;
use triagens\ArangoDb\CollectionHandler;
use triagens\ArangoDb\DocumentHandler;
use triagens\ArangoDb\Document;
use triagens\ArangoDb\Statement;

class ArangoModel
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var int
     */
    private $lastInsertId = null;
    /**
     * @var CollectionHandler
     */
    private $collectionHandler;
    /**
     * @var Collection
     */
    private $collection;
    /**
     * @var DocumentHandler
     */
    private $documentHandler;
    /**
     * @var Document;
     */
    private $document;
    /**
     * @var string
     */
    private $prepare = '';
    /**
     * @var string
     */
    private $bindValue = '';
    /**
     * @var string
     */
    private $vBindValue = '';
    /**
     * @var string
     */
    private $bindCollection = '';
    /**
     * @var string
     */
    private $vBindCollection = '';
    /**
     * ArangoModel constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }
    /**
     * @return CollectionHandler
     */
    private function collectionHandler()
    {
        if (!$this->collectionHandler) {
            $this->collectionHandler = new CollectionHandler($this->app['connection']);
        }
        return $this->collectionHandler;
    }
    /**
     * @return Collection
     */
    private function collection()
    {
        if(!$this->collection) {
            $this->collection = new Collection();
        }
        return $this->collection;
    }
    /**
     * @param string $nameCollection
     * @return bool
     * @throws \Exception
     */
    public function hasCollection($nameCollection)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter");
        }
        $collectionHandler = $this->collectionHandler();
        return $collectionHandler->has($nameCollection);
    }
    /**
     * @param string $nameCollection
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function deleteCollection($nameCollection, array $data = [])
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter - 1");
        }
        $collectionHandler = $this->collectionHandler();
        return $collectionHandler->drop($nameCollection,$data);
    }
    /**
     * @param string $newCollection
     * @return mixed|bool
     * @throws \Exception
     */
    public function createCollection($newCollection)
    {
        if (!(is_string($newCollection))) {
            throw new \Exception("Invalid value for parameter");
        }
        if (!$this->hasCollection($newCollection)) {
            $collection = $this->collection();
            $collection->setName($newCollection);
            $collectionHandler = $this->collectionHandler();
            return $collectionHandler->create($collection);
        }
        return false;
    }
    /**
     * @return DocumentHandler
     */
    private function documentHandler()
    {
        if (!$this->documentHandler) {
            $this->documentHandler = new DocumentHandler($this->app['connection']);
        }
        return $this->documentHandler;
    }
    /**
     * @return Document
     */
    private function document()
    {
        if (!$this->document) {
            $this->document = new Document();
        }
        return $this->document;
    }
    /**
     * @param string $nameCollection
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function createDocument($nameCollection, array $data)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter");
        }
        $document = $this->document();
        foreach ($data as $key => $value) {
            $document->set((string)$key,$value);
        }
        $documentHandler = $this->documentHandler();
        $this->lastInsertId = $documentHandler->save($nameCollection,$document);
        return $this->lastInsertId;
    }
    /**
     * @return null|id
     */
    public function lastInsertId()
    {
        return $this->lastInsertId;
    }
    /**
     * @param string $nameCollection
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function hasDocument($nameCollection, $id)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter - 1");
        }
        if (!(is_int($id))) {
            throw new \Exception("Invalid value for parameter - 2");
        }
        $documentHandler = $this->documentHandler();
        return $documentHandler->has($nameCollection,$id);
    }
    /**
     * @param string $nameCollection
     * @param int $id
     * @return Document
     * @throws \Exception
     */
    public function getDocument($nameCollection, $id)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter - 1");
        }
        if (!(is_int($id))) {
            throw new \Exception("Invalid value for parameter - 2");
        }
        $documentHandler = $this->documentHandler();
        return $documentHandler->get($nameCollection,$id);
    }
    /**
     * @param string $nameCollection
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function updateDocument($nameCollection, $id, array $data)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter - 1");
        }
        if (!(is_int($id))) {
            throw new \Exception("Invalid value for parameter - 2");
        }
        $documentHandler = $this->documentHandler();
        $document = $this->getDocument($nameCollection,$id);
        foreach ($data as $key => $value) {
            $document->set((string)$key,$value);
        }
        return $documentHandler->update($document);
    }
    /**
     * parameter - 1: Instance of Document $document or array with [$nameCollection => $id]
     * parameter - 2: type value String
     * @param Document|array $document
     * @param string $attribute
     * @return bool
     * @throws \Exception
     */
    public function removeAttributeDocument($document, $attribute)
    {
        if (!($document instanceof Document) || !(is_array($document))) {
            throw new \Exception("Invalid value for parameter - 1");
        }
        if (empty($attribute) || !(is_string($attribute))) {
            throw new \Exception("Invalid value for parameter - 2");
        }
        $doc = [];
        $documentHandler = $this->documentHandler();
        if($document instanceof Document) {
            $doc = $document;
        }
        if (is_array($document)) {
            foreach ($document as $nameCollection => $id) {
                $doc = $this->getDocument((string)$nameCollection,(int)$id);
            }
        }
        if ($doc) {
            unset($doc->$attribute);
            return $documentHandler->replace($doc);
        }
        return false;
    }
    /**
     * @param string $nameCollection
     * @param Document $currentDocument
     * @param Document $newDocument
     * @return bool
     * @throws \Exception
     */
    public function replaceDocument($nameCollection, Document $currentDocument, Document $newDocument)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter");
        }
        $documentHandler = $this->documentHandler();
        return $documentHandler->replaceById($nameCollection, $currentDocument->getId(),$newDocument);
    }
    /**
     * @param Document|array $document
     * @return bool
     * @throws \Exception
     */
    public function removeDocument($document)
    {
        if (!($document instanceof Document) || !(is_array($document))) {
            throw new \Exception("Invalid value for parameter");
        }
        $doc = [];
        $documentHandler = $this->documentHandler();
        if($document instanceof Document) {
            $doc = $document;
        }
        if (is_array($document)) {
            foreach ($document as $nameCollection => $id) {
                $doc = $this->getDocument((string)$nameCollection,(int)$id);
            }
        }
        if ($doc) {
            return $documentHandler->remove($doc);
        }
        return false;
    }
    /**
     * @param string $queryAQL
     * @return ArangoModel $this
     * @throws \Exception
     */
    public function prepare($queryAQL)
    {
        if (!(is_string($queryAQL))) {
            throw new \Exception("Invalid value for parameter");
        }
        $this->prepare = $queryAQL;
        return $this;
    }
    /**
     * @param array $bindValue
     * @return ArangoModel $this
     */
    public function bindValue(array $bindValue)
    {
        foreach ($bindValue as $bind => $value) {
            $this->bindValue = (string) $bind;
            $this->vBindValue = (string) $value;
        }
        return $this;
    }
    /**
     * @param array $bindCollection
     * @return $this
     */
    public function bindCollection(array $bindCollection)
    {
        foreach ($bindCollection as $bind => $collection) {
            $this->bindCollection = (string) '@' . $bind;
            $this->vBindCollection = (string) $collection;
        }
        return $this;
    }
    /**
     * @return Document array
     */
    public function execute()
    {
        $statement = new Statement(
            $this->app['connection'],
            [
                'query' => $this->prepare,
                'bindVars' => [
                    $this->bindCollection => $this->vBindCollection,
                    $this->bindValue => $this->vBindValue
                ]
            ]
        );
        $result = $statement->execute();
        return $result->getAll();
    }
    /**
     * @param string $aql
     * @return Document array
     */
    public function query($aql)
    {
        $statement = new Statement(
            $this->app['connection'],
            [
                'query' => $aql,
            ]
        );
        $result = $statement->execute();
        return $result->getAll();
    }
    /**
     * @param string $nameCollection
     * @param array $document [ "key" => "needle"]
     * @return \triagens\ArangoDb\cursor
     * @throws \Exception
     */
    public function searchInDocument($nameCollection, array $document)
    {
        if (!(is_string($nameCollection))) {
            throw new \Exception("Invalid value for parameter");
        }
        $collectionHandler = $this->collectionHandler();
        $cursor = $collectionHandler->byExample($nameCollection,$document);
        return $cursor->getAll();
    }
}