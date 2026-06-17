<?php
namespace Mtr\MiniCRM\API\V1\Request;

readonly class CustomersRequestFactory
{
    /**
     * @return CustomersRequest
     */
    public function create(
        string $q,
        string $status,
        int $page,
        int $limit
    ): CustomersRequest {
        return new CustomersRequest(
            query: $q,
            status: $status,
            page: $page,
            limit: $limit
        );
    }
}