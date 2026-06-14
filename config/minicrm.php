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
        ],
    ],
];