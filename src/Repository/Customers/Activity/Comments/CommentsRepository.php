<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity\Comments;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class CommentsRepository implements CommentsRepositoryInterface
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
    public function get(int $id): ?ActiveRow
    {
        return $this->select()->where('id = ?', $id)->fetch();
    }

    /**
     * @inheritDoc
     */
    public function byActivity(ActiveRow $activity, bool $withDetails = true): Selection
    {
        return $activity->related(self::TABLE_NAME)
            ->order('created_at DESC');
        ;
    }

    public function add($activity_id, string $content): ActiveRow
    {
        return $this->explorer->table(self::TABLE_NAME)->insert([
            'customer_activity_id' => $activity_id,
            'comment' => $content,
            'created_at' => new \DateTime(),
        ]);
    }


    /**
     * @return Selection
     */
    private function select(array $columns = ['*']): Selection
    {
        $columns = join(', ', $columns);
        return $this->explorer->table(self::TABLE_NAME)->select($columns);
    }
}