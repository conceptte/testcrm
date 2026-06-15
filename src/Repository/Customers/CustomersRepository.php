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
        return $this->withStats($this->select())->where('public_id = ?', $publicId)->fetch();
    }

    /**
     * @inheritDoc
     */
    public function search(
        ?string $query = null,
        ?bool $isActive = null,
        ?string $order = null,
        bool $includeStats = true,
    ): Selection
    {
        $selection = $includeStats ? $this->withStats($this->select()) : $this->select();
        return $this->applyFilters(
            $selection,
            $query,
            $isActive,
        )
        ->group(self::TABLE_NAME . '.id')
        ->order($this->getOrder($order));
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

    /**
     * Add activities and comments count to selection.
     * 
     * @param Selection $selection
     * 
     * @return Selection
     */
    private function withStats(Selection $selection): Selection
    {
        return $selection
            ->select(self::TABLE_NAME . '.*, COUNT(DISTINCT :customer_activities.id) AS activities_count, COUNT(DISTINCT :customer_activities:activity_comments.id) AS comments_count')
            ->group(self::TABLE_NAME . '.id');
    }

    /**
     * Get order by clause for customer queries.
     * 
     * @param string|null $sort
     * 
     * @return string
     */
    private function getOrder(?string $sort = null): string
    {
        $column = match ($sort) {
            'name',  => $sort,
            'email' => $sort,
            'active' => 'is_active DESC',
            'inactive' => 'is_active ASC',
            default => 'id',
        };

        $column = sprintf('%s.%s', self::TABLE_NAME, $column);

        return $column;
    }
}