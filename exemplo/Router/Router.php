<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 02:40
 */
namespace Exemplo\Router;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

class Router implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->get('/',"index:getIndex");
    }
}