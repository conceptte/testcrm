<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity;

use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

interface ActivityRepositoryInterface
{
    const TABLE_NAME = 'customer_activities';
    
    /**
     * Get activities by customer
     * 
     * @param ActiveRow $customer
     * 
     * @return Selection
     */
    public function byCustomer(ActiveRow $customer): Selection;
}