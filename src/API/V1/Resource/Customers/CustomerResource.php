<?php

namespace Mtr\MiniCRM\API\V1\Resource\Customers;


use Mtr\MiniCRM\API\V1\Resource\ResourceInterface;
use Nette\Database\Table\ActiveRow;

readonly class CustomerResource implements ResourceInterface
{
    /**
     * @param ActiveRow $customer
     */
    public function __construct(
        private ActiveRow $customer,
        private ?array $meta = null
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return
            [
                'id' => $this->customer->public_id,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
                'is_active' => $this->customer->is_active ? true : false,
                'total' => [
                    'activities' => $this->customer->activities_count ?? 'N/A',
                    'comments' => $this->customer->comments_count ?? 'N/A',
                ]
            ] 
            + ['meta' => $this->meta];
    }
}
