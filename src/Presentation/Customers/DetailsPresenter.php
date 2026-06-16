<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Exception\MiniCRMException;
use Mtr\MiniCRM\Exception\NotFoundException;
use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Throwable;

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
        try {
            $customer = $this->customersRepository->byPublicId($id);

            if ($customer === null) {
                throw new NotFoundException('Customer not found');
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
        } catch (MiniCRMException $e) {

            $this->error($e->getMessage());

        } catch (Throwable $e) {

            $this->error('An error occurred');
        }
    }

    protected function redrawAllControls(): void
    {
        parent::redrawAllControls();
        $this->redrawControl('activityList');
        $this->redrawControl('paginatorContainer');
    }

}
