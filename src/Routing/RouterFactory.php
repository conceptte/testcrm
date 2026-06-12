<?php
namespace Mtr\MiniCRM\Routing;

use Nette\Application\Routers\RouteList;

class RouterFactory
{
    use \Nette\StaticClass;

    /**
     * Create internal routes
     * 
     * @return RouteList
     * 
     * @throws \Nette\InvalidStateException
     */
    public static function create(): RouteList
    {
        $router = new RouteList('MiniCRM');
        
        $router
            ->add(ApiRouteFactory::create())
            ->add(CustomersRouteFactory::create())
        ;

        return $router;
    }
}