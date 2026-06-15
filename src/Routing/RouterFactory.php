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
        $group = new RouteList();

        $minicrm = (new RouteList('MiniCRM'))->add(CustomersRouteFactory::create());
        $api = (new RouteList('MiniCRMAPI'))->add(ApiRouteFactory::create());

        $group
            ->add($minicrm)
            ->add($api);

        return $group;
    }
}