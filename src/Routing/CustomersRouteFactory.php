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

        $router->addRoute('minicrm', [
            'presenter' => 'Customers',
            'action' => 'index',
        ]);

        $router->addRoute('minicrm/customers', [
            'presenter' => 'Customers',
            'action' => 'index',
        ]);

        $router->addRoute('minicrm/customers/<id>', [
            'presenter' => 'Customers:Details',
            'action' => 'view',
        ]);

        $router->addRoute('minicrm/customers/<id>/activity/<activity>', [
            'presenter' => 'Customers:Activity',
            'action' => 'view',
        ]);

        return $router;
    }
}