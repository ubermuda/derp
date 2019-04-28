<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser;

use PhpParser\Node;

/**
 * Thrown when a node could not be parsed.
 */
class CouldNotProcessNodeException extends \RuntimeException
{
    private $node;

    public function __construct(Node $node, \Throwable $previous)
    {
        parent::__construct('Error while parsing node', 0, $previous);

        $this->node = $node;
    }

    public function getNode(): Node
    {
        return $this->node;
    }
}
