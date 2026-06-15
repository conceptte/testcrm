<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Mtr\MiniCRM\API\V1\Resource\CustomerCollectionResource;
use Mtr\MiniCRM\API\V1\Resource\CustomerResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

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
        $limit = min($limit, self::MAX_PAGE_SIZE);

        $active = CustomerStatus::isActive($status);

        $customers = $this->search($q, $active)->page($page, $limit);

        $this->sendJson([
            'data' => iterator_to_array($this->formatCustomers($customers)),
            'meta' => $this->formatMetadata($customers, [
                'q' => $q,
                'status' => $status,
                'page' => $page,
                'limit' => $limit,
            ]),
        ]);
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
     * @param Selection $selection
     * @param array $params
     * 
     * @return array
     */
    private function formatMetadata(Selection $selection, array $params): array
    {
        $total = $this->customerRepository->count($selection);
        return [
            'params' => $params,
            'pagination' => [
                'total' => $total,
                'current' => $params['page'] ?? 1,
                'per_page' => self::PAGE_SIZE,
                'total_pages' => ceil($total / self::PAGE_SIZE),
            ],
        ];
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