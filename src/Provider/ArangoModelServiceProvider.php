<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 22/01/17
 * Time: 18:26
 */
namespace JSantos\Provider;

use JSantos\ArangoModel\ArangoModel;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class ArangoModelServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['arango.model'] = new ArangoModel($app);
    }
}