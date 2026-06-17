<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Exception\NotFoundException;
use Mtr\MiniCRM\API\V1\Exception\ValidationException;
use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Mtr\MiniCRM\API\V1\Resource\CustomerResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Throwable;

class CustomersPresenter extends ApiPresenter
{
    const PAGE_SIZE = 10;
    const MAX_PAGE_SIZE = 100;

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
        $request = [
            'q' => $q,
            'status' => $status,
            'page' => $page,
            'limit' => $limit,
        ];

        try{
            $this->validateParams($request);

            $active = CustomerStatus::isActive($request['status']);
            $customers = $this->search($request['q'], $active)->page($request['page'], $request['limit']);

            if ($customers->count() < 1) {
                throw new NotFoundException('No customers found');
            }

            $apiData = $this->formatApiData($customers, $request);
            
        } catch (ApiExceptionInterface $e) {

            $apiData = $this->errorData($e->getMessage());

        } catch (Throwable $e) {

            $apiData = $this->errorData($e->getMessage());

        }

        $this->sendJson($apiData);
    }

    /**
     * @param string $q
     * @param bool|null $active
     * 
     * @return Selection
     */
    private function search(string $q, ?bool $active = null): Selection
    {
        //$active = CustomerStatus::isActive($active);
        return $this->customerRepository->search($q, $active);
    }

    /**
     * @param Selection $customers
     * @param array{q:string,status:?string,page:int,limit:int} $request
     * 
     * @return array
     */
    private function formatApiData(
        Selection $customers, 
        array $request
    ): array
    {
        $total = $this->customerRepository->count($customers);

        return [
            'success' => true,
            'request' => $request,
            'pagination' => [
                'total' => $total,
                'current' => $request['page'],
                'per_page' => $request['limit'],
                'total_pages' => ceil($total / $request['limit']),
            ],
            'data' => iterator_to_array($this->formatCustomers($customers)),
        ];
    }

    /**
     * @param Selection $selection
     * 
     * @return \Generator
     */
    private function formatCustomers(Selection $selection): \Generator
    {
        foreach ($selection as $customer) {
            yield CustomerResource::fromRow($customer, $this->addons($customer));
        }
    }

    /**
     * @param ActiveRow $customer
     * 
     * @return array
     */
    private function addons(ActiveRow $customer): array
    {
        return [
            'links' => [
                'self' => $this->link('Customers:Details:', ['id' => $customer->public_id]),
            ],
        ];
    }

    /**
     * 
     * @param array{q:string,status:?string,page:int,limit:int} $request
     * 
     * @return void
     * 
     * @throws ValidationException
     */
    private function validateParams(array $request): void
    {
        if ($request['page'] < 1) {
            throw new ValidationException('Page must be greater than 0');
        }

        if ($request['limit'] < 1 || $request['limit'] > self::MAX_PAGE_SIZE) {
            throw new ValidationException('Limit must be between 1 and ' . self::MAX_PAGE_SIZE);
        }

        if ($request['status'] && !CustomerStatus::isValid($request['status'])) {
            throw new ValidationException('Invalid status value');
        }
    }
}