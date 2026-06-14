<?php
namespace Mtr\MiniCRM\Repository\Customers;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class CustomersRepository implements CustomersRepositoryInterface
{

    /**
     * @param Explorer $explorer
     */
    public function __construct(
        private Explorer $explorer,
    )
    {}

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->select(['COUNT(id) AS total'])->fetch()->total;
    }

    /**
     * @inheritDoc
     */
    public function get(int $id): ?ActiveRow
    {
        return $this->select()->where('id = ?', $id)->fetch();
    }

    /**
     * @inheritDoc
     */
    public function byPublicId(string $publicId): ?ActiveRow
    {
        return $this->select()->where('public_id = ?', $publicId)->fetch();
    }

    /**
     * @inheritDoc
     */
    public function search(
        int $page, int $perPage = self::PAGE_SIZE
    ): Selection
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        
        $select = $this->select([
                'customers.*',
                'COUNT(:customer_activities.id) AS activities_count',
            ])
            ->group('id')
            ->page($page, $perPage);

        return $select;
    }



    /**
     * @param array $columns
     * @param mixed ...$params
     * 
     * @return Selection
     */
    private function select(array $columns = ['*'], mixed ...$params): Selection
    {
        $columns = join(', ', $columns);
        return $this->explorer->table(self::TABLE_NAME)->select($columns, ...$params);
    }

}