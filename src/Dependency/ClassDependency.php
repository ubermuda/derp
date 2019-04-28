<?php

declare(strict_types=1);

namespace LambdaPackager\Dependency;

use ReflectionClass;

class ClassDependency extends FileDependency
{
    private $className;

    public function __construct(string $filePath, string $className, ?FileDependency $parent = null)
    {
        $this->className = $className;

        parent::__construct($filePath, $parent);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public static function fromClassName(string $className, ?FileDependency $parent = null): self
    {
        $filePath = (new ReflectionClass($className))->getFileName();

        return new self($filePath, $className, $parent);
    }
}
