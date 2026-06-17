<?php
namespace Mtr\MiniCRM\API\V1\Resource;

use JsonSerializable;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class CustomerCollectionResource implements JsonSerializable
{
    /**
     * @param Selection $customers
     * @param callable|null $linkGenerator
     */
    public function __construct(
        private Selection $customers,
        private $linkGenerator = null
    )
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return iterator_to_array($this->formatCustomers());
    }

    /**
     * @return \Generator<CustomerResource>
     */
    private function formatCustomers(): \Generator
    {
        foreach ($this->customers as $customer) {
            yield new CustomerResource(
                customer: $customer,
                params: $this->links($customer)
            );
        }
    }

    /**
     * @param ActiveRow $customer
     * 
     * @return array<string, mixed>
     */
    private function links(ActiveRow $customer): array
    {
        if (!$this->linkGenerator) {
            return [];
        }
        
        return [
            'links' => [
                'self' =>($this->linkGenerator)('Customers:Details:', ['id' => $customer->public_id]),
            ],
        ];
    }
}
