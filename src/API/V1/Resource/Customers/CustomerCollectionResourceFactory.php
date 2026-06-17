<?php
namespace Mtr\MiniCRM\API\V1\Resource\Customers;

use Nette\Database\Table\Selection;

class CustomerCollectionResourceFactory
{
    /**
     * @param CustomerResourceFactory $customerResourceFactory
     */
    public function __construct(
        private CustomerResourceFactory $customerResourceFactory
    ) {}

    /**
     * @param Selection $customers
     * @return CustomerCollectionResource
     */
    public function create(Selection $customers): CustomerCollectionResource
    {
        return new CustomerCollectionResource(
            collection: $customers,
            resourceFactory: $this->customerResourceFactory
        );
    }
}