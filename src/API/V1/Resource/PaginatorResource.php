<?php
namespace Mtr\MiniCRM\API\V1\Resource;

use JsonSerializable;

class PaginatorResource implements JsonSerializable
{
    public function __construct(
        private int $total,
        private int $current,
        private int $perPage,
        private int $totalPages
    )
    {
    }

    /**
     * @return array<string, int>
     */
    public function jsonSerialize(): array
    {
        return [
            'total' => $this->total,
            'current' => $this->current,
            'per_page' => $this->perPage,
            'total_pages' => $this->totalPages,
        ]; 
    }
}