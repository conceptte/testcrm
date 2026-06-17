<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Exception\NotFoundException;
use Mtr\MiniCRM\API\V1\Exception\ValidationException;
use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Mtr\MiniCRM\API\V1\Resource\CustomerResource;
use Mtr\MiniCRM\API\V1\Request\CustomersRequest;
use Mtr\MiniCRM\API\V1\Resource\CustomerCollectionResource;
use Mtr\MiniCRM\API\V1\Resource\PaginatorResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Throwable;

class CustomersPresenter extends ApiPresenter
{
    const PAGE_SIZE = 10;
    const MAX_PAGE_SIZE = 100;

    private ?CustomersRequest $request = null;

    public function __construct(
        private CustomersRepositoryInterface $customerRepository
    )
    {}
    /**
     * List customers
     * 
     * @return void
     */
    public function actionDefault(
        string $q = '', 
        ?string $status = null,
        int $page = 1, 
        int $limit = self::PAGE_SIZE
    ): void
    {
        try{

            $this->request = (new CustomersRequest(
                query: $q,
                status: $status ?? '',
                page: $page,
                limit: $limit
            ))->validate();
            
            $customers = $this->search()
                ->page($this->request->page, $this->request->limit);

            if ($customers->count() < 1) {
                throw new NotFoundException('No customers found');
            }

            $apiData = $this->formatData($customers);
            
        } catch (ApiExceptionInterface $e) {

            $apiData = $this->errorData($e->getMessage());

        } catch (Throwable $e) {

            $apiData = $this->errorData($e->getMessage());

        }

        $this->sendJson($apiData);
    }


     /**
      * @return Selection
      */
    private function search(): Selection
    {
        return $this->customerRepository
            ->search(
                $this->request->query, 
                CustomerStatus::isActive($this->request->status)
            );
    }

    /**
     * @param Selection $customers
     * 
     * @return array
     */
    private function formatData(Selection $customers)
    {
        return  [
                'success' => true,
                'request' => $this->request,
                'pagination' => $this->getPaginatorResource($customers),
                'data' => $this->getCustomerCollectionResource($customers)
            ];
    }  

    /**
     * @param Selection $customers
     * 
     * @return CustomerCollectionResource
     */
    private function getCustomerCollectionResource(Selection $customers): CustomerCollectionResource
    {
        return new CustomerCollectionResource(
            customers: $customers,
            linkGenerator: fn(string $path, array $params = []) => $this->link($path, $params)
        );
    }

    /**
     * @param Selection $customers
     * 
     * @return PaginatorResource
     */
    private function getPaginatorResource(Selection $customers): PaginatorResource
    {
        return new PaginatorResource(
            total: $customers->count('*'),
            current: $this->request->page,
            perPage: $this->request->limit,
            totalPages: ceil($customers->count('*') / $this->request->limit),
        );
    }
}