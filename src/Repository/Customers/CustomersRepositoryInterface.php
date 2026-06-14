<?php
namespace Mtr\MiniCRM\Repository\Customers;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

interface CustomersRepositoryInterface
{
    const TABLE_NAME = 'customers';
    const PAGE_SIZE = 20;

    
    /**
     * @param Selection $selection
     * 
     * @return int
     */
    public function count(Selection $selection): int;

    /**
     * Get customer by ID
     * 
     * @param int $id
     * 
     * @return ActiveRow|null
     */
    public function get(int $id): ?ActiveRow;

    /**
     * Get customer by public ID
     * 
     * @param string $publicId
     * 
     * @return ActiveRow|null
     */
    public function byPublicId(string $publicId): ?ActiveRow;

    /**
     * search customers
     * 
     * @param string|null $query Search by name or email
     * @param bool|null $isActive Filter active status
     * 
     * @return \Nette\Database\Table\Selection
     */
    public function search(
        ?string $query = null,
        ?bool $isActive = null,
    ): Selection;


}