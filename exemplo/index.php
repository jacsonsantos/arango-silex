<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 01:45
 */
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use Silex\Application;

    $app = new Application;

    $app['debug'] = true;

    //Registrando Service Controller
    $app->register(new \Silex\Provider\ServiceControllerServiceProvider());
    //Registrando Rotas
    $app->register(new Exemplo\Router\Router());
    //Registrando Service Provider do Controller Index
    $app->register(new Exemplo\Provider\IndexServiceProvider());
    //Registrando Conexao com ArangoDB
    $app->register(new \Exemplo\Provider\ConnectionArango());

    $app->run();
