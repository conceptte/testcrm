<?php

namespace Mtr\MiniCRM\API\V1\Presentation;

use Nette\Database\Table\Selection;
use Nette\DI\Attributes\Inject;
use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Request\CustomersRequest;
use Mtr\MiniCRM\API\V1\Request\CustomersRequestFactory;
use Mtr\MiniCRM\API\V1\Resource\Customers\CustomerCollectionResourceFactory;
use Mtr\MiniCRM\API\V1\Resource\Paginator\PaginatorResourceFactory;
use Mtr\MiniCRM\API\V1\Resource\PaginatorResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;
use Throwable;

class CustomersPresenter extends ApiPresenter
{
    const PAGE_SIZE = 10;
    const MAX_PAGE_SIZE = 100;

    const META_REQUEST = 'request';
    const META_PAGINATION = 'pagination';

    #[Inject]
    public PaginatorResourceFactory $paginatorResourceFactory;

    public function __construct(
        private CustomersRequestFactory $requestFactory,
        private CustomersRepositoryInterface $customerRepository,
        private CustomerCollectionResourceFactory $customerResourceCollectionFactory,
    ) {}

    /**
     * List customers
     * 
     * @return void
     */
    public function actionDefault(
        string $q = '',
        string $status = '',
        int $page = 1,
        int $limit = self::PAGE_SIZE
    ): void {

        $resource = [];

        try {
            $request = $this->requestFactory->create(
                q: $q,
                status: $status,
                page: $page,
                limit: $limit
            )->validate();

            $customers = $this->search($request);

            $resource =
                $this->customerResourceCollectionFactory->create($customers);

            $resource
                ->withMeta(self::META_REQUEST, $request)
                ->withMeta(
                    self::META_PAGINATION,
                    $this->createPaginatorResource(
                        $resource->count,
                        $request->page,
                        $request->limit
                    )
                )
                ->withLinkGenerator(fn($customer) => $this->link("Customers:Details:", ['id' => $customer->public_id]))
            ;

        } catch (ApiExceptionInterface $e) {
            $resource = $this->errorData($e->getMessage());
        } catch (Throwable $e) {
            $resource = $this->errorData();
        } finally {
            $this->sendJson($resource);
        }
    }

    /**
     * @return Selection
     */
    private function search(CustomersRequest $request): Selection
    {
        $customers = $this->customerRepository
            ->search(
                $request->query,
                CustomerStatus::isActive($request->status)
            )
            ->page($request->page, $request->limit);

        return $customers;
    }

    /**
     * @param int $count
     * @param int $page
     * @param int $limit
     * 
     * @return PaginatorResource
     */
    private function createPaginatorResource(
        int $count, 
        int $page,
        int $limit
    ): PaginatorResource
    {
        return $this->paginatorResourceFactory->createResource(
            total: $count,
            current: $page,
            perPage: $limit,
            totalPages: ceil($count / $limit),
        );
    }
}
