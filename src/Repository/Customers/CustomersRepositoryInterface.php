<?php
namespace Mtr\MiniCRM\Repository\Customers;

interface CustomersRepositoryInterface
{
    public function get(int $id): ?\Nette\Database\Table\ActiveRow;

}