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
        $router = new RouteList('MiniCRMAPIV1');
        
        $router->addRoute('minicrm/api/v1/ping', [
            'presenter' => 'Ping',
            'action' => 'pong',
        ]);

        $router->addRoute('minicrm/api/v1/customers', [
            'presenter' => 'Customers',
            'action' => 'default',
        ]);

        $router->addRoute('minicrm/api/v1/customers/<id>', [
            'presenter' => 'Customers:Details',
            'action' => 'default',
        ]);

        return $router;
    }
}