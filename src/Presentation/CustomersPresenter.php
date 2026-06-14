<?php

namespace Mtr\MiniCRM\Presentation;

use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Application\Attributes\Persistent;

class CustomersPresenter extends MiniCRMPresenter
{
    use AwarePaginator;

    #[Persistent]
    public string $q = '';

    #[Persistent]
    public ?string $status = null;

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
     * @param int $page
     * 
     * @return void
     */
    public function renderIndex(int $page = 1): void
    {
        $status = $this->status !== null ? strtolower(trim($this->status)) : null;
        
        $isActive = match ($status) {
            'active' => true,
            'inactive' => false,
            default => null,
        };

        $customers = $this->customersRepository->search(
            $this->q,
            $isActive,
        )->page($page, CustomersRepositoryInterface::PAGE_SIZE);

        $totalCount = $this->customersRepository->count($customers);

        $this->paginationControl
            ->count($totalCount)
            ->pageSize(CustomersRepositoryInterface::PAGE_SIZE)
            ->page($page);

        $this->template->q = trim((string) $this->q);
        $this->template->status = $status;
        $this->template->totalCount = $totalCount;

        $this->template->customers = $customers;

    }

    
}
