<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity;

use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class ActivityRepository implements ActivityRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function byCustomer(ActiveRow $customer): Selection
    {
        return $customer->related(self::TABLE_NAME)
             ->select(self::TABLE_NAME . '.*, COUNT(:activity_comments.id) AS comments_count')
             ->group(self::TABLE_NAME . '.id')
             ->order('created_at DESC');
    }
}