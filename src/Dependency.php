<?php

declare(strict_types=1);

namespace LambdaPackager;

use ArrayIterator;
use IteratorAggregate;

class Dependency implements IteratorAggregate
{
    /** @var self[] */
    private $children = [];

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

    public function createChild(string $filePath): Dependency
    {
        $child = new self($filePath);
        $this->add($child);

        return $child;
    }

    /** @return Dependency[] */
    public function all(): array
    {
        return $this->children;
    }

    public function add(self $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /** @param Dependency[] $children */
    public function addAll(array $children): self
    {
        foreach ($children as $child) {
            $this->add($child);
        }

        return $this;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->children);
    }
}
