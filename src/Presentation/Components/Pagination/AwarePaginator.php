<?php
namespace Mtr\MiniCRM\Presentation\Components\Pagination;

use Nette\Application\Attributes\Persistent;

trait AwarePaginator
{
    #[Persistent]
    public int $page = 1;

    /**
     * Pagination 
     * 
     * @return PaginationControl
     */
    protected function createComponentPagination(): PaginationControl
    {
        return $this->paginationControl;
    }

    /**
     * @return int
     */
    protected function page(): int
    {
        return $this->page > 0 ? $this->page : 1;
    }
}