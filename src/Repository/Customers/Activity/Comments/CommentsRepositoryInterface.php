<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity\Comments;

use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

interface CommentsRepositoryInterface
{
    const TABLE_NAME = 'activity_comments';
    
    /**
     * @param int $id
     * 
     * @return ActiveRow|null
     */
    public function get(int $id): ?ActiveRow;

    /**
     * @param ActiveRow $activity
     * 
     * @return Selection
     */
    public function byActivity(ActiveRow $activity): Selection;

    /**
     * @param int $activity_id
     * @param string $content
     * 
     * @return ActiveRow
     */
    public function add($activity_id, string $content): ActiveRow;
}