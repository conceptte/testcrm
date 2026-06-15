<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;

class ActivityPresenter extends MiniCRMPresenter
{
    use AwarePaginator;

    const PAGE_SIZE = 2;

   public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private ActivityRepositoryInterface $activityRepository,
        private CommentsRepositoryInterface $commentsRepository,
        private PaginationControl $paginationControl,
    ) {}

    public function actionComment(string $id, int $activity): void
    {
        //die('actionComment');
        if ($this->isAjax()) {
            $this->paginationControl->isAjax();
        } else {
            $this->redirect('this');
        }


        $activity = $this->activityRepository->get($activity);

        $comments = $this->commentsRepository->byActivity($activity)
            ->page($this->page+1, self::PAGE_SIZE);
        
        $this->redrawControl('commentsList');

        return;

        // $customer = $this->customersRepository->byPublicId($id);

        // $activity = $this->activityRepository->get($activity);

        // if ($activity === null) {
        //     $this->error('Activity not found');
        // }

        // if ($activity->customer_id !== $customer->id) {
        //     $this->error('Activity does not belong to this customer');
        // }

        // $commentText = trim((string) $this->getHttpRequest()->getPost('comment'));

        // if ($commentText === '') {
        //     $this->flashMessage('Comment cannot be empty', 'error');
        //     $this->redirect('this');
        // }

        // $this->commentsRepository->add($activity, $commentText);

        // $this->flashMessage('Comment added successfully', 'success');
        // $this->redirect('this');
    }

    /**
     * @param string $id
     * 
     * @return void
     */
    public function renderView(string $id, int $activity ): void
    {
        $customer = $this->customersRepository->byPublicId($id);

        $activity = $this->activityRepository->get($activity);

        if ($activity === null) {
            $this->error('Activity not found');
        }

        if ($activity->customer_id !== $customer->id) {
            $this->error('Activity does not belong to this customer');
        }

        $comments = $this->commentsRepository->byActivity($activity)
            ->page($this->page, self::PAGE_SIZE);
        
        $totalCount = $activity->related('activity_comments')->count();

        $this->paginationControl
            ->isAjax()
            ->count($totalCount)
            ->pageSize(self::PAGE_SIZE)
            ->page($this->page);
        
        $this->template->customer = $customer;
        $this->template->activity = $activity;
        $this->template->comments = $comments;
        $this->template->totalCount = $totalCount;

        if ($this->isAjax()) {
            
            $this->redrawControl('commentsList');
            $this->redrawControl('paginatorContainer');
        }
    }

}
