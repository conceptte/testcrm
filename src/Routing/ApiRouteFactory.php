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
        $router = new RouteList('API');
        
        $router->addRoute('minicrm/api/v1/ping', [
            'presenter' => 'Ping',
            'action' => 'pong',
        ]);

        return $router;
    }
}