<?php
namespace Mtr\MiniCRM\Routing;

use Nette\Application\Routers\RouteList;

final class CustomersRouteFactory
{
    use \Nette\StaticClass;

    /**
     * Create Customer routes
     * 
     * @return RouteList
     * 
     * @throws \Nette\InvalidStateException
     */
    public static function create(): RouteList
    {
        $router = new RouteList();

        $router->addRoute('minicrm[/<presenter>[/<action>]]', [
            'presenter' => 'Customers',
            'action' => 'index',
        ]);

        return $router;
    }
}