<?php
namespace Mtr\MiniCRM;

use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;
use Nette\Schema\Schema;
use Nette\Schema\Expect;

/**
 * @todo: schema validation for config
 */
final class MiniCRMExtension extends CompilerExtension
{
    /** @var array<string, mixed> */
    private array $extConfig = [];

    // public function getConfigSchema(): Schema
    // {
    //     //debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);die();
    //     return Expect::structure([
    //         'mapping' => Expect::listOf('string'),
    //         'services' => Expect::listOf('string'),
    //         'test' => Expect::string()->required(),
    //     ]);
    // }

    /**
     * @inheritDoc
     */
    public function loadConfiguration(): void
    {        
        $this->extConfig = $this->loadFromFile(__DIR__ . '/../config/minicrm.php') ?? [];

//bdump($this->extConfig, 'MiniCRM config');

        $this->setupDefinitions();
    }

    public function beforeCompile(): void
    {
        $this->mapPresenters();
    }

    /**
     * Load service definitions from config
     * 
     * @return static
     */
    private function setupDefinitions(): static
    {
        $this->loadDefinitionsFromConfig($this->config(ConfigNode::Services));

        return $this;
    }

    /**
     * Map minicrm presenters
     * 
     * @return static
     * 
     * @throws MissingServiceException
     */
    private function mapPresenters(): static
    {
        $this->getContainerBuilder()->hasDefinition('application.presenterFactory') 
            || throw new MissingServiceException('PresenterFactory service not found');

        $this->getContainerBuilder()
            ->getDefinition('application.presenterFactory')
                ->addSetup('setMapping', [$this->config(ConfigNode::Mapping)]);

        return $this;
    }

    /**
     * @param ConfigNode $node
     * 
     * @return array<string, mixed>
     */
    private function config(ConfigNode $node): array
    {
        $root = ConfigNode::Root;
        return $this->extConfig[ $root() ][ $node() ] 
            ??  throw new \InvalidArgumentException(
                    sprintf("Configuration section '%s' not found in 'minicrm' config.", $node())
                );
    }
}