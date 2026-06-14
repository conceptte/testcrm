<?php
namespace Mtr\MiniCRM\Repository\Customers;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class CustomersRepository implements CustomersRepositoryInterface
{
    const TABLE_NAME = 'customers';
    const PAGE_SIZE = 20;

    public function __construct(
        private Explorer $explorer,
    )
    {}

    public function get(int $id): ?ActiveRow
    {
        return $this->select()->where('id = ?', $id)->fetch();
    }


    private function select(array $columns = ['*']): Selection
    {
        return $this->explorer->table(self::TABLE_NAME)->select(...$columns);
    }

}