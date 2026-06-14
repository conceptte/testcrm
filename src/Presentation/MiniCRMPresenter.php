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
        array_shift($parts); // remove first part (module name)

        $path = join(DIRECTORY_SEPARATOR, array_map(fn ($part) => ucfirst($part), $parts));

        return [
            __DIR__ . "/templates/$path/{$this->getView()}.latte",
        ];
    }
    
}