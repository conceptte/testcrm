<?php
namespace Mtr\MiniCRM\API\V1\Resource\Paginator;

use Mtr\MiniCRM\API\V1\Resource\PaginatorResource;

class PaginatorResourceFactory
{
    /**
     * @return PaginatorResource
     */
    public function createResource(int $total, int $current, int $perPage, int $totalPages): PaginatorResource
    {
        return new PaginatorResource(
            total: $total,
            current: $current,
            perPage: $perPage,
            totalPages: $totalPages
        );
    }
}