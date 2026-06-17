<?php
namespace Mtr\MiniCRM\API\V1\Resource;

use Nette\Database\Table\ActiveRow;

interface ResourceFactoryInterface
{
    /**
     * @return ResourceInterface
     */
    public function createResource(ActiveRow $resource, array $meta = []): ResourceInterface;
}