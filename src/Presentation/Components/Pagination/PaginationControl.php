<?php
namespace Mtr\MiniCRM\Presentation\Components\Pagination;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

final class PaginationControl extends Control
{
    /**
     * @param Paginator $paginator
     */
    public function __construct(
        private Paginator $paginator
    ) {}

    /**
     * @return Paginator
     */
    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    /**
     * total count
     * 
     * @param int $count
     * 
     * @return static
     */
    public function count(int $count): static
    {
        $this->paginator->setItemCount($count);

        return $this;
    }

    /**
     * items per page
     * 
     * @param int $size
     * 
     * @return static
     */
    public function pageSize(int $size): static
    {
        $this->paginator->setItemsPerPage($size);

        return $this;
    }

    /**
     * current page
     * 
     * @param int $page
     * 
     * @return static
     */
    public function page(int $page): static
    {
        $this->paginator->setPage($page);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        $this->template->paginator = $this->paginator;
        $this->template->presenter = $this->getPresenter();
        
        $this->template->render(__DIR__ . '/pagination.latte');
    }

    /**
     * Generates link for given page number
     * 
     * @param int $page
     * 
     * @return string
     */
    public function pageLink(int $page): string
    {
        return $this->getPresenter()->link('this', ['page' => $page]);
    }
}