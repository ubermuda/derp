<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Tree\Node;
use LambdaPackager\Manifest;

class FileHandlerRegistry
{
    /** @var FileHandler[] */
    private $handlers;

    /** @var FileHandler */
    private $defaultHandler;

    public function __construct(Manifest $manifest)
    {
        $this->handlers = [
            'php' => new PhpFileHandler($manifest),
        ];

        $this->defaultHandler = new DefaultFileHandler();
    }

    public function getHandler(Node $dependency): FileHandler
    {
        $extension = pathinfo($dependency->getValue(), PATHINFO_EXTENSION);

        return $this->handlers[$extension] ?? $this->defaultHandler;
    }
}
