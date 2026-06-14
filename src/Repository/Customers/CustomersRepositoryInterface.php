<?php
namespace Mtr\MiniCRM\Repository\Customers;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

interface CustomersRepositoryInterface
{
    const TABLE_NAME = 'customers';
    const PAGE_SIZE = 20;

    /**
     * Count total customers
     * 
     * @return int
     */
    public function count(): int;

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
     * @param int $page
     * @param int $perPage
     * 
     * @return \Nette\Database\Table\Selection
     */
    public function search(
        int $page, int $perPage = self::PAGE_SIZE
    ): Selection;


}