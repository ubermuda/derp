<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser;

/**
 * Thrown when a class could not be autoload.
 */
class CouldNotAutoloadClassException extends \RuntimeException
{
    public function __construct(string $className)
    {
        parent::__construct(sprintf('Could not autoload class "%s"', $className));
    }
}
