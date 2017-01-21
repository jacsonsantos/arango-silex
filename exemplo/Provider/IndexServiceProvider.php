<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 02:49
 */
namespace Exemplo\Provider;

use Exemplo\Controllers\IndexController;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class IndexServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['index'] = new IndexController($app);
    }
}