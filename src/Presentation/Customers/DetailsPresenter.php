<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;

class DetailsPresenter extends MiniCRMPresenter
{
    use AwarePaginator;

    const PAGE_SIZE = 5;

   public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private ActivityRepositoryInterface $activityRepository,
        private PaginationControl $paginationControl,
    ) {
        parent::__construct();
    }

    /**
     * @param string $id
     * 
     * @return void
     */
    public function renderView(string $id): void
    {
        
        $customer = $this->customersRepository->byPublicId($id);

        if ($customer === null) {
            $this->error('Customer not found');
        }

        $activities = $this->activityRepository->byCustomer($customer)
            ->page($this->page(), self::PAGE_SIZE);

        $this->paginationControl
            ->isAjax()
            ->count($this->customersRepository->count($activities))
            ->pageSize(self::PAGE_SIZE)
            ->page($this->page());

        $this->template->customer = $customer;
        $this->template->activities = $activities;

        if ($this->isAjax()) {
            $this->redrawAllControls();
        }
    }

    protected function redrawAllControls(): void
    {
        parent::redrawAllControls();
        $this->redrawControl('activityList');
        $this->redrawControl('paginatorContainer');
    }

}
