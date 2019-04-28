<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Dependency\FileDependency;
use LambdaPackager\Manifest;

class FileHandlerRegistry
{
    /** @var FileHandler[] */
    private $handlers;

    public function __construct(Manifest $manifest)
    {
        $this->handlers = [
            new PhpFileHandler($manifest),
            new DefaultFileHandler(),
        ];
    }

    public function getHandler(FileDependency $dependency): FileHandler
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($dependency)) {
                return $handler;
            }
        }
    }
}
