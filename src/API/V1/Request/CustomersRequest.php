<?php
namespace Mtr\MiniCRM\API\V1\Request;

use JsonSerializable;
use Mtr\MiniCRM\API\V1\Exception\ValidationException;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;

class CustomersRequest implements JsonSerializable
{
    private const MAX_PAGE_SIZE = 100;

    public function __construct(
        public readonly string $query = '',
        public readonly string $status = '',
        public readonly int $page = 1,
        public readonly int $limit = 10,
    ) {
        
    }

    /**
     * @return static
     * 
     * @throws ValidationException
     */
    public function validate(): static
    {
        if ($this->page < 1) {
            throw new ValidationException('Page must be greater than 0');
        }

        if ($this->limit < 1 || $this->limit > self::MAX_PAGE_SIZE) {
            throw new ValidationException('Limit must be between 1 and ' . self::MAX_PAGE_SIZE);
        }

        if ($this->status && !CustomerStatus::isValid($this->status)) {
            throw new ValidationException('Invalid status value');
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return (array) get_object_vars($this);
    }

}