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
     * 
     * @return void
     */
    public function renderIndex(int $page = 1): void
    {
        $paginator = $this->paginator
            ->setItemCount($this->customersRepository->count())
            ->setItemsPerPage(CustomersRepositoryInterface::PAGE_SIZE)
            ->setPage($page);

        $this->template->paginator = $paginator;

        $this->template->customers = $this->customersRepository->search(
            $paginator->getPage(),
            $paginator->getItemsPerPage(),
        );
        
    }
}
