<?php
namespace Mtr\MiniCRM\Presentation;

use Nette\Application\UI\Presenter;

abstract class MiniCRMPresenter extends Presenter
{
    /**
     * @inheritDoc
     */
    public function formatLayoutTemplateFiles(): array
    {
        return [
            __DIR__ . '/templates/@layout.latte',
        ];
    }

    /**
     * @inheritDoc
     */
    public function formatTemplateFiles(): array
    {
        $name = $this->getName();

        $parts = explode(':', $name);

        $presenter = end($parts);

        return [
            __DIR__ . "/templates/$presenter/{$this->getView()}.latte",
        ];
    }
    
}