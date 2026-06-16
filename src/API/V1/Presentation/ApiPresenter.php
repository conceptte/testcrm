<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Nette\Application\Attributes\Persistent;

abstract class ApiPresenter extends \Nette\Application\UI\Presenter
{
    #[Persistent]
    public string $version = 'v1';

    /**
     * @param string $message
     * 
     * @return array
     */
    protected function errorData(string $message = 'An error occurred'): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}