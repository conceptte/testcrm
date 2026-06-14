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
    public function count(?string $query = null, ?bool $isActive = null): int
    {
        return $this->applyFilters(
            $this->select(['COUNT(id) AS total']),
            $query,
            $isActive,
        )->fetch()->total;
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
        int $page,
        int $perPage = self::PAGE_SIZE,
        ?string $query = null,
        ?bool $isActive = null,
    ): Selection
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        return $this->applyFilters(
            $this->select([
                'customers.*',
                'COUNT(:customer_activities.id) AS activities_count',
            ]),
            $query,
            $isActive,
        )
            ->group('id')
            ->page($page, $perPage);
    }

    /**
     * Apply common list filters for customer queries.
     */
    private function applyFilters(Selection $selection, ?string $query, ?bool $isActive): Selection
    {
        $query = trim((string) $query);

        if ($query !== '') {
            $selection->where('(name LIKE ? OR email LIKE ?)', "%{$query}%", "%{$query}%");
        }

        if ($isActive !== null) {
            $selection->where('is_active = ?', $isActive);
        }

        return $selection;
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