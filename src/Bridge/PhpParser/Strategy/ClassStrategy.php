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
        return (class_exists($className) || interface_exists($className)) && false === (new ReflectionClass($className))->isInternal();
    }
}
