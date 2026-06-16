<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Mtr\MiniCRM\API\V1\Exception\ApiExceptionInterface;
use Mtr\MiniCRM\API\V1\Exception\NotFoundException;
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
    public function actionIndex(
        string $q = '', 
        string $status = 'active',
        int $page = 1, 
        int $limit = self::PAGE_SIZE
    ): void
    {
        try{
            $limit = min($limit, self::MAX_PAGE_SIZE);

            $active = CustomerStatus::isActive($status);

            $customers = $this->search($q, $active)->page($page, $limit);

            if ($customers->count() < 1) {
                throw new NotFoundException('No customers found');
            }

            $apiData = $this->formatApiData($customers, $q, $status, $page, $limit);
            
        } catch (ApiExceptionInterface $e) {

            $apiData = $this->errorData($e->getMessage());

        } catch (Throwable $e) {

            $apiData = $this->errorData();

        }

        $this->sendJson($apiData);
    }

    /**
     * @param string $q
     * @param bool|null $active
     * 
     * @return Selection
     */
    private function search(string $q, bool $active): Selection
    {
        return $this->customerRepository->search($q, $active);
    }

    /**
     * @param Selection $customers
     * @param string $q
     * @param string $status
     * @param int $page
     * @param int $limit
     * 
     * @return array
     */
    private function formatApiData(Selection $customers, string $q, string $status, int $page, int $limit): array
    {
        $total = $this->customerRepository->count($customers);

        return [
            'success' => true,
            'request' => [
                'q' => $q,
                'status' => $status,
                'page' => $page,
                'limit' => $limit,
            ],
            'pagination' => [
                'total' => $total,
                'current' => $page ?? 1,
                'per_page' => $limit,
                'total_pages' => ceil($total / $limit),
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
                'self' => $this->link('Customers:Details:index', ['id' => $customer->public_id, 'version' => $this->version]),
            ],
        ];
    }
}