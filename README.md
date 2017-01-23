# Trabalhando com ArangoDB e Silex
Desenvolvi um _Model_ e _ServiceProvider_ que utiliza a Library oficial do ArangoDB para trabalhar com PHP, assim facilitando a manipulação de dados em sua aplicação.
## Adicionando Arango-Silex a minha Aplicação
```
composer require jsantos/arango-silex dev-master

```
## Estrutura de diretorio
Dentro de _src_ existe dois diretorios, _ArangoModel_ e _Provider_.
* Dentro de _Provider_ tem o Service (_ConnectionArangoServiceProvider.php_) de conexão com Banco de Dados ArangoDB.
* Em _ArangoModel_ temos o _Model_ (_ArangoModel.php_), onde possui as funcionalidades que ajudaram a munipular os dados de sua aplicação.

## Configurando conexão com ArangoDB
Dentro de sua aplicação, escolha um diretorio de sua preferencia para criar o arquivo de conexão com ArangoDB, após escolher um diretorio, siga os passos a seguir.
_Exemplo_: Criando arquivo de conexão.

Criaremos uma _class_ ConnectionArango que extende _ConnectionArangoServiceprovider_ e iniciaremos algumas configurações do banco ArangoDB.
Precisaremos apenas de 3 atributos, $database, $authUser e $authPassword.


Todos:
* protected $database
* protected $endpoint
* protected $authType
* protected $authUser
* protected $authPassword
* protected $connection
* protected $timeout
* protected $reconnect
* protected $create
* protected $updatePolicy


```php
<?php
namespace Exemplo\Provider;

use JSantos\Provider\ConnectionArangoServiceProvider;

class ConnectionArango extends ConnectionArangoServiceProvider
{
    /**
     * Nome de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $database = "meu_banco";
    /**
     * Usuario de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $authUser = "meu_usuario";
    /**
     * Senha de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $authPassword = "minha_senha";
}

```
Diretorio/Arquivo: exemplo/Provider/_ConnectionArango.php_

## Registrando Conexão no Silex
Registrando _ConnectionArango_ que acabamos de fazer.
```php
<?php
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use Silex\Application;

    $app = new Application;

    $app['debug'] = true;

    //Registrando Service Controller
    $app->register(new \Silex\Provider\ServiceControllerServiceProvider());
    //Registrando Conexao com ArangoDB
    $app->register(new \Exemplo\Provider\ConnectionArango());

    $app->run();

```
## Como usar o ArangoModel
Primeiro devemos registrar o Service Provider do ArangoModel(ArangoModelServiceProvider).
```php
<?php
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use Silex\Application;

    $app = new Application;

    $app['debug'] = true;

    //Registrando Service Controller
    $app->register(new \Silex\Provider\ServiceControllerServiceProvider());
    //Registrando Conexao com ArangoDB
    $app->register(new \Exemplo\Provider\ConnectionArango());
    //Registrando Service Provider de ArangoModel
    $app->register(new \JSantos\Provider\ArangoModelServiceProvider());

    $app->run();
```
## Metodos ArangoModel
* createCollection(string $newCollection)
* hasCollection(string $nameCollection)
* deleteCollection(string $nameCollection, array $data = [])
* createDocument(string $nameCollection, array $data)
* lastInsertId()
* hasDocument(string $nameCollection,$idDocument)
* getDocument(string $nameCollection, $idDocument)
* updateDocument(string $nameCollection, $idDocument, array $data)
* removeAttributeDocument(Document|array $document, string $attribute)
* replaceDocument(string $nameCollection, Document $currentDocument, Document $newDocument)
* removeDocument(Document|array $document)
* prepare(string $queryAQL)
* bindCollection(array $bindCollection)
* bindValue(array $bindValue)
* execute()
* query(string $queryAQL)
* searchInDocument(string $nameCollection, array $document)

### Criando Collection
```php
<?php
    $arango = $this->app['arango.model'];
    
    $newCollection = "users";
    
    if (!$arango->createCollection($newCollection)) {
        echo "error";
    }
    echo "success!";
```
### Verifica se já existe Collection
```php
<?php
    $arango = $this->app['arango.model'];
    
    $myCollection = "users";
    
    if ($arango->hasCollection($myCollection)) {
        echo "exist";
    }
    echo "no exist";
```
### Deletando Collection
```php
<?php
    $arango = $this->app['arango.model'];
    
    $myCollection = "users";
    
    if ($arango->hasCollection($myCollection)) {
        $arango->deleteCollection($myCollection);
    }
```
### Criando Documentos em Collection
```php
<?php
    $arango = $this->app['arango.model'];
   
    $collection = "users";
    
    if (!$arango->hasCollection($collection)) {
        $arango->createCollection($collection);
    }
    
    $data = [
        "email" => "jacsonk47@gmail.com",
        "password" => password_hash("jacson", PASSWORD_BCRYPT, ["cost"=>12])
    ];
    
   $idDocument =  $arango->createDocument($collection,$data);
```