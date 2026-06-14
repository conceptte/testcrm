<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Utils\Paginator;
use Nette\Application\Attributes\Persistent;

class CustomersPresenter extends MiniCRMPresenter
{
    #[Persistent]
    public string $q = '';

    #[Persistent]
    public ?string $status = null;

    /**
     * @param CustomersRepositoryInterface $customersRepository
     */
    public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private Paginator $paginator,
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

        $paginator = $this->paginator
            ->setItemCount($this->customersRepository->count($customers))
            ->setItemsPerPage(CustomersRepositoryInterface::PAGE_SIZE)
            ->setPage($page);

        $this->template->paginator = $paginator;
        $this->template->q = trim((string) $this->q);
        $this->template->status = $status;

        $this->template->customers = $customers;

    }
}
