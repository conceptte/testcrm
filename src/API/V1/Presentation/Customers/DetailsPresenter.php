<?php
namespace Mtr\MiniCRM\API\V1\Presentation\Customers;

use Mtr\MiniCRM\API\V1\Resource\CustomerResource;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;

class DetailsPresenter extends Presenter
{
    public function __construct(
        private CustomersRepositoryInterface $customerRepository
    )
    {}

    /**
     * Get customer details
     * 
     * @param string $id Customer public ID
     * 
     * @return void
     */
    public function actionIndex(string $id): void
    {
        if (!$customer = $this->customerRepository->byPublicId($id)) {
            $this->error('Customer not found');
        }

        $this->sendJson(CustomerResource::fromRow($customer));
    }

    
}