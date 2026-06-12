<?php
namespace Mtr\MiniCRM;

/**
 * Config nodes for MiniCRM configuration
 */
enum ConfigNode: string
{
    case Root = 'minicrm';
    case Mapping = 'mapping';
    case Services = 'services';

    /**
     * Accessor for value
     * e.g. (ConfigNode::Mapping)() will return 'mapping'
     * or $node = ConfigNode::Mapping; $node() will return 'mapping'
     * 
     * @return string
     */
    public function __invoke(): string
    {
        return $this->value;
    }
}