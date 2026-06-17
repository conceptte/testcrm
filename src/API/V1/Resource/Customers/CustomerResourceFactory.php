<?php
namespace Mtr\MiniCRM\API\V1\Resource\Customers;

use Mtr\MiniCRM\API\V1\Resource\ResourceFactoryInterface;
use Mtr\MiniCRM\API\V1\Resource\ResourceInterface;
use Mtr\MiniCRM\API\V1\Resource\Customers\CustomerResource;
use Nette\Database\Table\ActiveRow;

class CustomerResourceFactory implements ResourceFactoryInterface
{
    /**
     * @return ResourceInterface
     */
    public function createResource(ActiveRow $resource, array $meta = []): ResourceInterface
    {
        return new CustomerResource(
            customer: $resource, 
            meta: $meta
        );
    }
}