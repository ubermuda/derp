<?php

declare(strict_types=1);

namespace Derp\Bridge\PhpParser\Strategy;

use Derp\Bridge\PhpParser\CouldNotAutoloadClassException;
use Derp\ManifestAware;
use Derp\ManifestAwareTrait;
use ReflectionClass;

abstract class ClassStrategy implements Strategy, ManifestAware
{
    use ManifestAwareTrait;

    protected function getClassFileName(string $className): string
    {
        return (new ReflectionClass($className))->getFileName();
    }

    protected function isProcessable(string $className): bool
    {
        if ($this->manifest->hasExcludeClass($className)) {
            return false;
        }

        if (!(class_exists($className) || trait_exists($className) || interface_exists($className))) {
            throw new CouldNotAutoloadClassException($className);
        }

        if ((new ReflectionClass($className))->isInternal()) {
            return false;
        }

        return true;
    }
}
