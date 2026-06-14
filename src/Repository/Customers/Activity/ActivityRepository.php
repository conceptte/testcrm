<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity;

use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

class ActivityRepository implements ActivityRepositoryInterface
{
    
    public function byCustomer(ActiveRow $customer): Selection
    {
        return $customer->related('customer_activities')
             ->select('customer_activities.*, COUNT(:activity_comments.id) AS comments_count')
             ->group('customer_activities.id')
             ->order('created_at DESC');
    }
}