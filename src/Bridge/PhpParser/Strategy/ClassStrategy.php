<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\PhpParser\Strategy;

use LambdaPackager\Bridge\PhpParser\CouldNotAutoloadClassException;
use LambdaPackager\Manifest;
use ReflectionClass;

abstract class ClassStrategy implements Strategy, ManifestAwareStrategy
{
    use ManifestAwareStrategyTrait;

    protected function getClassFileName(string $className): string
    {
        return (new ReflectionClass($className))->getFileName();
    }

    protected function isProcessable(string $className): bool
    {
        if ($this->manifest->hasExcludeClass($className)) {
            return false;
        }

        if (!(class_exists($className) || interface_exists($className))) {
            throw new CouldNotAutoloadClassException($className);
        }

        if ((new ReflectionClass($className))->isInternal()) {
            return false;
        }

        return true;
    }
}
