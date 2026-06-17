<?php
namespace Mtr\MiniCRM\Presentation\Customers;

use Nette\Application\UI\Presenter;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Throwable;

class QuickSearchPresenter extends Presenter
{

public function __construct(
        private  CustomersRepositoryInterface $customersRepository,
    ) {}
    
    public function startup(): void
    {
        parent::startup();

        if (!$this->isAjax()) {
            $this->error('This page is only accessible via AJAX');
        }
    }

    
    public function renderDefault(string $q = ''): void
    {
        try {
            $q = trim($q);
            $suggestions = $this->customersRepository->search($q)->limit(5);
            $this->template->suggestions = $suggestions;
            
        } catch (Throwable $e) {
            
        }
    }
}
