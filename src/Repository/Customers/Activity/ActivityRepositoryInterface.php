<?php
namespace Mtr\MiniCRM\Repository\Customers\Activity;

use Nette\Database\Table\Selection;
use Nette\Database\Table\ActiveRow;

interface ActivityRepositoryInterface
{
    const TABLE_NAME = 'customer_activities';

    /**
     * @param int $id
     * 
     * @return ActiveRow|null
     */
    public function get(int $id): ?ActiveRow;

    /**
     * Get activities by customer
     * 
     * @param ActiveRow $customer
     * 
     * @return Selection
     */
    public function byCustomer(ActiveRow $customer): Selection;
}