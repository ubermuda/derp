<?php

declare(strict_types=1);

namespace LambdaPackager\Dependency;

use LambdaPackager\Tree\Node;

class FileDependency extends Node
{
    public function __construct(string $filePath, ?Node $parent = null)
    {
        parent::__construct($filePath, $parent);
    }

    public function getFilePath(): string
    {
        return $this->getValue();
    }
}
