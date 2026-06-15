<?php
namespace Mtr\MiniCRM\Routing;

use Nette\Application\Routers\RouteList;

final class ApiRouteFactory
{
    use \Nette\StaticClass;

    /**
     * API routes
     * 
     * @return RouteList
     * 
     * @throws \Nette\InvalidStateException
     */
    public static function create(): RouteList
    {
        $router = new RouteList();
        
        $router->addRoute('minicrm/api/<version>/ping', [
            'presenter' => 'Ping',
            'action' => 'pong',
        ]);

        $router->addRoute('minicrm/api/<version>/customers', [
            'presenter' => 'Customers',
            'action' => 'index',
        ]);

        $router->addRoute('minicrm/api/<version>/customers/<id>', [
            'presenter' => 'Customers:Details',
            'action' => 'index',
        ]);

        return $router;
    }
}