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
    public function count(Selection $selection): int
    {
        return $selection->count('*');
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
        ?string $query = null,
        ?bool $isActive = null,
        bool $includeStats = true,
    ): Selection
    {
        return $this->applyFilters(
            $this->select([
                'customers.*',
                $includeStats ? 'COUNT(:customer_activities.id) AS activities_count' : null,
                $includeStats ? 'COUNT(:customer_activities:activity_comments.id) AS comments_count' : null,
            ]),
            $query,
            $isActive,
        )
        ->group('id');
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