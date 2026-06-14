<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Utils\Paginator;
use Nette\Application\Attributes\Persistent;

class DetailsPresenter extends MiniCRMPresenter
{
    const PAGE_SIZE = 5;

    #[Persistent]
    public ?int $page = 1;

   public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private ActivityRepositoryInterface $activityRepository,
        private Paginator $paginator,
    ) {
        parent::__construct();
    }

    public function renderView(string $id): void
    {
        
        $customer = $this->customersRepository->byPublicId($id);

        if ($customer === null) {
            $this->error('Customer not found');
        }

        $activities = $this->activityRepository->byCustomer($customer)
            ->page($this->page, self::PAGE_SIZE);

        $paginator = $this->paginator
            ->setItemCount($this->customersRepository->count($activities))
            ->setItemsPerPage(self::PAGE_SIZE)
            ->setPage($this->page);

        $this->template->customer = $customer;
        $this->template->activities = $activities;
        $this->template->paginator = $paginator;
    }
    
}
