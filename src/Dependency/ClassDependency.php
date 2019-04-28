<?php

declare(strict_types=1);

namespace LambdaPackager\Dependency;

use LambdaPackager\Dependency\FileDependency;
use LambdaPackager\Tree\Node;
use ReflectionClass;

class ClassDependency extends FileDependency
{
    private $className;

    public function __construct(string $filePath, string $className, ?Node $parent = null)
    {
        $this->className = $className;

        parent::__construct($filePath, $parent);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public static function fromClassName(string $className, ?Node $parent = null): self
    {
        $filePath = (new ReflectionClass($className))->getFileName();

        return new self($filePath, $className, $parent);
    }
}
