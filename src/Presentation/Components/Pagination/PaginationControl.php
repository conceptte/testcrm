<?php
namespace Mtr\MiniCRM\Presentation\Components\Pagination;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class PaginationControl extends Control
{
    private bool $isAjax = false;
    /**
     * @param Paginator $paginator
     */
    public function __construct(
        protected Paginator $paginator
    ) {}

    /**
     * @return Paginator
     */
    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    /**
     * Enable or disable AJAX mode
     * 
     * @param bool $isAjax
     * 
     * @return static
     */
    public function isAjax(bool $isAjax = true): static
    {
        $this->isAjax = $isAjax;

        return $this;
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
        $this->paginator->setPage($page > 0 ? $page : 1);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        $this->template->paginator = $this->paginator;
        $this->template->presenter = $this->getPresenter();
        $this->template->isAjax = $this->isAjax;

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