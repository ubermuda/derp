<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Dependency;

class FileHandlerRegistry
{
    /** @var FileHandler[] */
    private $handlers;

    /** @var FileHandler */
    private $defaultHandler;

    public function __construct()
    {
        $this->handlers = [
            'php' => new PhpFileHandler(),
        ];

        $this->defaultHandler = new DefaultFileHandler();
    }

    public function getHandler(Dependency $dependency): FileHandler
    {
        $extension = pathinfo($dependency->getFilePath(), PATHINFO_EXTENSION);

        return $this->handlers[$extension] ?? $this->defaultHandler;
    }
}
