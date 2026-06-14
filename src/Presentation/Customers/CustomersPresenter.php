<?php
namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;

class CustomersPresenter extends MiniCRMPresenter
{   
    public function __construct(
        private CustomersRepositoryInterface $customersRepository,
    )
    {
        bdump($this->customersRepository, 'CustomersRepository');
        die();
    }
}