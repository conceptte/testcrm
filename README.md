# Test MiniCRM - Nette Edition

Installation and setup instructions:

Composer:
    - `composer require conceptte/testcrm`

GIT:
    - `git clone https://github.com/conceptte/testcrm.git`

Nette:
    - add extension to configuration:
        ```
        extensions:
            minicrm: Mtr\MiniCRM\MiniCRMExtension
        ```
Extension routing:
To enable extension routing it need to update `app/Core/RouterFactory.php`:
TIP: refer to Nette documentation for more information about routing: https://doc.nette.org/en/application/routing. Section "Order of Routes"

```php
    ///...
    public static function createRouter(): RouteList
	{
		$router = new RouteList;

		/**
		 * Minicrm routes		 * 
		 * @see \Mtr\MiniCRM\Routing\RouterFactory
		 */
		$router->add(\Mtr\MiniCRM\Routing\RouterFactory::create());

		//... other routes
	}
    //...
```

No other configuration is needed.
Custom extension configuration located at 'config/minicrm.php' and it is loaded automatically by extension.
it contains presenter mapping, services configuration:

```php
<?php
return [
    'minicrm' => [
        'mapping' => [
            'MiniCRM' => 'Mtr\MiniCRM\Presentation\*\*Presenter',
        ],
        'services' => [
            'paginator' => Nette\Utils\Paginator::class,
            'paginationControl' => Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl::class,
            'customersRepository' =>[
                'type' => Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface::class,
                'create' => Mtr\MiniCRM\Repository\Customers\CustomersRepository::class,
            ],
            'activityRepository' => [
                'type' => Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface::class,
                'create' => Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepository::class,
            ],
            'commentsRepository' => [
                'type' => Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepositoryInterface::class,
                'create' => Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepository::class,
            ],
        ],
    ],
];
```

Why php and not neon? it is more flexible to use f.e. classes and interfaces instead of pure strings.


Architecture overview:
- The extension is designed to be modular and easily integrable into any Nette application.


