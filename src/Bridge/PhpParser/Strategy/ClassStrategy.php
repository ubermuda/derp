<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use ReflectionClass;

abstract class ClassStrategy implements Strategy
{
    protected function getClassFileName(string $className): string
    {
        return (new ReflectionClass($className))->getFileName();
    }

    protected function isProcessable(string $className): bool
    {
        if (!(class_exists($className) || interface_exists($className))) {
            throw new \RuntimeException(sprintf('Found class "%s" but could not autoload it', $className));
        }

        if ((new ReflectionClass($className))->isInternal()) {
            return false;
        }

        return true;
    }
}
