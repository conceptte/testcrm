<?php

namespace Mtr\MiniCRM\Presentation\Customers;

use Mtr\MiniCRM\Exception\Comment\ValidationException;
use Mtr\MiniCRM\Presentation\Components\Pagination\AwarePaginator;
use Mtr\MiniCRM\Presentation\Components\Pagination\PaginationControl;
use Mtr\MiniCRM\Presentation\MiniCRMPresenter;
use Mtr\MiniCRM\Repository\Customers\Activity\ActivityRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\Activity\Comments\CommentsRepositoryInterface;
use Mtr\MiniCRM\Repository\Customers\CustomersRepositoryInterface;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class ActivityPresenter extends MiniCRMPresenter
{
    use AwarePaginator;

    #[Persistent]
    public string $id;

    #[Persistent]
    public int $activity;

    const PAGE_SIZE = 10;

   public function __construct(
        private CustomersRepositoryInterface $customersRepository,
        private ActivityRepositoryInterface $activityRepository,
        private CommentsRepositoryInterface $commentsRepository,
        private PaginationControl $paginationControl,
    ) {}

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
            ->page($this->page(), self::PAGE_SIZE);
        
        $totalCount = $activity->related('activity_comments')->count();

        $this->paginationControl
            ->isAjax()
            ->count($totalCount)
            ->pageSize(self::PAGE_SIZE)
            ->page($this->page());
        
        $this->template->customer = $customer;
        $this->template->activity = $activity;
        $this->template->comments = $comments;
        $this->template->totalCount = $totalCount;

        if ($this->isAjax()) {
            $this->redrawAllControls();
        }
    }

    /**
     * @return Form
     */
    protected function createComponentCommentForm(): Form
    {
        $form = new Form();
        $form->addProtection();

        $form->setHtmlAttribute('class', 'ajax comment-form');

        $form->addTextArea('comment', '')
            ->setRequired('Please enter a comment')
            ->addRule(Form::MinLength, 'Comment must be at least %d characters long', 3)
            ->addRule(Form::MaxLength, 'Comment cannot be longer than %d characters', 2048)
            ->setHtmlAttribute('placeholder', 'Add a comment...')
            ->setHtmlAttribute('rows', 5)
            ;
        $form->addSubmit('submit', 'Comment');

        $form->onSuccess[] = [$this, 'handleCommentForm'];

        return $form;
    }

    /**
     * @param Form $form
     * @param ArrayHash $data
     * 
     * @return void
     */
    public function handleCommentForm(Form $form, ArrayHash $data): void
    {
        try {
            $activity = $this->activityRepository->get($this->activity);
            if ($activity === null) {
                throw new ValidationException('Activity not found');
            }

            if ($activity->customer_id !== $this->customersRepository->byPublicId($this->id)?->id) {
                throw new ValidationException('Activity does not belong to this customer');
            }

            $comment = trim($data->comment);
            if ($comment === '') {
                throw new ValidationException('Comment cannot be empty');
            }

            $this->commentsRepository->add($activity->id, $comment);

            $form->setValues(['comment' => ''], true);

            $this->flashMessage('Comment added successfully', 'success');

        } catch (ValidationException $e) {
            $this->flashMessage($e->getMessage(), 'error');
        } catch (\Throwable $e) {
            $this->flashMessage('An error occurred while adding the comment', 'error');
        }
    }

    /**
     * @inheritDoc
     */
    protected function redrawAllControls(): void
    {
        parent::redrawAllControls();

        $this->redrawControl('commentsCount');
        $this->redrawControl('commentForm');
        $this->redrawControl('commentsList');
        $this->redrawControl('paginatorContainer');
    }

}
