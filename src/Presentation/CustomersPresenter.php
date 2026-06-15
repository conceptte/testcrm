<?php

namespace Mtr\MiniCRM\Presentation;

use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomerStatus;
use Nette\Application\Attributes\Persistent;

class CustomersPresenter extends MiniCRMPresenter
{
    use AwarePaginator;

    #[Persistent]
    public string $q = '';

    #[Persistent]
    public ?string $status = null;

    #[Persistent]
    public ?string $sort = null;

    /**
     * @param CustomersRepositoryInterface $customersRepository
     */
    public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private PaginationControl $paginationControl,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function renderIndex(): void
    {
        $status = $this->status !== null ? strtolower(trim($this->status)) : null;
        
        $isActive = CustomerStatus::isActive($status);

        $customers = $this->customersRepository->search(
            $this->q,
            $isActive,
            $this->sort
        )
        ->page($this->page(), CustomersRepositoryInterface::PAGE_SIZE);

        $totalCount = $this->customersRepository->count($customers);

        $this->paginationControl
            ->isAjax()
            ->count($totalCount)
            ->pageSize(CustomersRepositoryInterface::PAGE_SIZE)
            ->page($this->page());

        $this->template->q = trim((string) $this->q);
        $this->template->status = $status;
        $this->template->sort = $this->sort;
        $this->template->isActive = $isActive;
        $this->template->totalCount = $totalCount;

        $this->template->customers = $customers;

        if ($this->isAjax()) {
            $this->redrawAllControls();
        }

    }

    /**
     * @inheritDoc
     */
    protected function redrawAllControls(): void
    {
        parent::redrawAllControls();
        $this->redrawControl('statusControls');
        $this->redrawControl('searchForm');
        $this->redrawControl('sortControls');
        $this->redrawControl('totalCount');
        $this->redrawControl('customersList');
        $this->redrawControl('paginatorContainer');
    }
    
}
