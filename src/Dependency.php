<?php

declare(strict_types=1);

namespace LambdaPackager;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Dependency implements IteratorAggregate
{
    /** @var self[] */
    private $children;

    /** @var string */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = realpath($filePath);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function createChild(string $filePath)
    {
        $child = new self($filePath);
        $this->add($child);

        return $child;
    }

    public function add(self $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function addAll(array $children): self
    {
        foreach ($children as $child) {
            $this->add($child);
        }

        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->children);
    }
}
