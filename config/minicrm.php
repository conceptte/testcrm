<?php
use Nette\Utils\Paginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepository;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepository;
use Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepository;

return [
    'minicrm' => [
        'mapping' => [
            'MiniCRM' => 'Mtr\MiniCRM\Presentation\*\*Presenter',
            'MiniCRMAPIV1' => 'Mtr\MiniCRM\API\V1\Presentation\*\*Presenter',
        ],
        'services' => [
            'paginator' => Paginator::class,
            'paginationControl' => PaginationControl::class,
            'customersRepository' =>[
                'type' => CustomersRepositoryInterface::class,
                'create' => CustomersRepository::class,
            ],
            'activityRepository' => [
                'type' => ActivityRepositoryInterface::class,
                'create' => ActivityRepository::class,
            ],
            'commentsRepository' => [
                'type' => CommentsRepositoryInterface::class,
                'create' => CommentsRepository::class,
            ],
        ],
    ],
];