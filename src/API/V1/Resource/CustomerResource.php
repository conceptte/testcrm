<?php
namespace Mtr\MiniCRM\API\V1\Resource;

use JsonSerializable;
use Nette\Database\Table\ActiveRow;
use Nette\DI\Attributes\Inject;

class CustomerResource implements JsonSerializable
{
    /**
     * @param ActiveRow $customer
     */
    public function __construct(
        private ActiveRow $customer,
        private array $params = []
    )
    {}

    /**
     * @param ActiveRow $customer
     * 
     * @return static
     */
    public static function fromRow(ActiveRow $customer, array $params = []): static
    {
        return new static($customer, $params);
    }

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
                'totals' => [
                    'total_activities' => $this->customer->activities_count ?? 0,
                    'total_comments' => $this->customer->comments_count ?? 0,
            ]
            ] + $this->params;
    }
}