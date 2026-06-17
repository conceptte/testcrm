<?php
namespace Mtr\MiniCRM\API\V1\Resource;


use Nette\Database\Table\Selection;
use Generator;
use JsonSerializable;

class CollectionResource implements ResourceInterface
{
    /**
     * pre-calculated count of the collection, to avoid multiple queries
     * 
     * @var int
     */
    public int $count;

    /**
     * @var callable|null
     */
    private $linkGenerator = null;

    /**
     * @var array<string, JsonSerializable|array>
     */
    private array $meta = [];

    /**
     * @param Selection $collection
      * @param ResourceFactoryInterface $resourceFactory
     */
    public function __construct(
        private Selection $collection,
        private ResourceFactoryInterface $resourceFactory
    ){
        $primary = $collection->getPrimary();
        $primary = !is_array($primary) && $primary ? $primary : '*';

        $this->count = $this->collection->count($primary);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->formatPipe();
    }

    /**
     * @param string $section
     * @param JsonSerializable|array $data
     * 
     * @return static
     */
    public function withMeta(string $section, JsonSerializable|array $data): static
    {
        $this->meta[$section] = $data;

        return $this;
    }

    public function withLinkGenerator(callable $linkGenerator): static
    {
        $this->linkGenerator = $linkGenerator;

        return $this;
    }

    
    /**
     * @return array<string, mixed>
     */
    protected function formatPipe(): array
    {
        return 
            $this->formatSuccess()
            + $this->formatMeta()
            + $this->formatData();
    }

    /**
     * @return array<string, bool>
     */
    protected function formatSuccess(): array
    {
        return [static::NODE_SUCCESS => true];
    }

    /**
     * @return array<string, JsonSerializable|array>
     */
    protected function formatMeta(): array
    {
        return $this->meta;
    }

    /**
     * I used iterator_to_array() to:
     * 1. use resourceFactory to format each resource in the collection
     * 2. skip the keys of the collection, which are not needed in the API response
     * 
     * here could be lack of memory if the collection is too big, but in this case we are using pagination, so it should be fine
     * 
     * @return array<string, array>
     */
    protected function formatData(): array
    {
        return [static::NODE_DATA => iterator_to_array($this->formatCollection())];
    }

    /**
    * @return \Generator<ResourceInterface>
     */
    protected function formatCollection(): Generator
    {
        foreach ($this->collection as $resource) {
            yield $this->resourceFactory->createResource(
                resource: $resource,
                meta: $this->linkGenerator ? [static::NODE_META_URI => ($this->linkGenerator)($resource)] : []
            );
        }
    }
}