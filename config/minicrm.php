<?php
return [
    'minicrm' => [
        'mapping' => [
            'MiniCRM' => 'Mtr\MiniCRM\Presentation\*\**Presenter',
        ],
        'services' => [
            'customersRepository' =>[
                'type' => Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface::class,
                'create' => Mtr\MiniCRM\Repository\Customers\CustomersRepository::class,
            ],
        ],
    ],
];