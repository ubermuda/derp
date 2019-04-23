<?php

declare(strict_types=1);

namespace LambdaPackager;

use ReflectionClass;

class ClassDependency extends Dependency
{
    private $className;

    public function __construct(string $filePath, string $className)
    {
        $this->className = $className;

        parent::__construct($filePath);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public static function fromClassName(string $className): self
    {
        $filePath = (new ReflectionClass($className))->getFileName();

        return new self($filePath, $className);
    }
}
