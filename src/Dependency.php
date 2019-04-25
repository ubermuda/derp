<?php

declare(strict_types=1);

namespace LambdaPackager;

use ArrayIterator;
use IteratorAggregate;
use RuntimeException;

class Dependency implements IteratorAggregate
{
    /** @var Dependency|null */
    private $parent;

    /** @var self[] */
    private $children = [];

    /** @var string */
    private $filePath;

    public function __construct(string $filePath, ?Dependency $parent = null)
    {
        $this->filePath = realpath($filePath);
        $this->parent = $parent;

        if (null !== $parent) {
            $parent->add($this);
        }
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function setParent(Dependency $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /** @throws RuntimeException */
    public function getParent(): Dependency
    {
        if ($this->isRoot()) {
            throw new RuntimeException('Cannot get parent of a root node');
        }

        return $this->parent;
    }

    public function createChild(string $filePath): Dependency
    {
        return new self($filePath, $this);
    }

    /** @return Dependency[] */
    public function all(): array
    {
        return $this->children;
    }

    public function add(self $child): self
    {
        $child->setParent($this);

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

    /** @return Dependency[] */
    public function findInChildren(string $pattern): array
    {
        $results = [];

        foreach ($this->children as $child) {
            if (fnmatch($pattern, $child->getFilePath())) {
                $results[] = $child;
            }

            $results = array_merge($results, $child->findInChildren($pattern));
        }

        return $results;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->children);
    }
}
