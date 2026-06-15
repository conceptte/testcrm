<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Nette\Application\Attributes\Persistent;

abstract class ApiPresenter extends \Nette\Application\UI\Presenter
{
    #[Persistent]
    public string $version = 'v1';
}