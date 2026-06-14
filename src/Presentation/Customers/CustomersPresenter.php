<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Utils\Paginator;

class CustomersPresenter extends MiniCRMPresenter
{
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
     * @param string|null $q Search value for name/email
     * @param string|null $status active|inactive
     * 
     * @return void
     */
    public function renderIndex(int $page = 1, ?string $q = null, ?string $status = null): void
    {
        $status = $status !== null ? strtolower(trim($status)) : null;
        $isActive = match ($status) {
            'active' => true,
            'inactive' => false,
            default => null,
        };

        $paginator = $this->paginator
            ->setItemCount($this->customersRepository->count($q, $isActive))
            ->setItemsPerPage(CustomersRepositoryInterface::PAGE_SIZE)
            ->setPage($page);

        $this->template->paginator = $paginator;
        $this->template->q = trim((string) $q);
        $this->template->status = $status;

        $this->template->customers = $this->customersRepository->search(
            $paginator->getPage(),
            $paginator->getItemsPerPage(),
            $q,
            $isActive,
        );

    }
}
