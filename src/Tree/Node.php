<?php

declare(strict_types=1);

namespace LambdaPackager\Tree;

use ArrayIterator;
use IteratorAggregate;
use RuntimeException;

class Node implements IteratorAggregate
{
    /** @var Node|null */
    private $parent;

    /** @var self[] */
    private $children = [];

    /** @var mixed */
    private $value;

    public function __construct($value, ?Node $parent = null)
    {
        $this->value = realpath($value);
        $this->parent = $parent;

        if (null !== $parent) {
            $parent->add($this);
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function setParent(Node $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /** @throws RuntimeException */
    public function getParent(): Node
    {
        if ($this->isRoot()) {
            throw new RuntimeException('Cannot get parent of a root node');
        }

        return $this->parent;
    }

    public function createChild($value): Node
    {
        return new static($value, $this);
    }

    /** @return Node[] */
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

    /** @param Node[] $children */
    public function addAll(array $children): self
    {
        foreach ($children as $child) {
            $this->add($child);
        }

        return $this;
    }

    /** @return Node[] */
    public function filterChildren(callable $filter): array
    {
        $results = [];

        foreach ($this->children as $child) {
            if ($filter($child)) {
                $results[] = $child;
            }

            $results = array_merge($results, $child->filterChildren($filter));
        }

        return $results;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->children);
    }
}
