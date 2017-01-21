<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 02:55
 */
namespace Exemplo\Controllers;

use JSantos\ArangoModel\ArangoModel;
use Silex\Application;

class IndexController
{
    /**
     * @var Application
     */
    private $app;

    /**
     * IndexController constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        //Usando o Arango Model
        $arango = new ArangoModel($this->app);

        return "Hello, World";
    }
}