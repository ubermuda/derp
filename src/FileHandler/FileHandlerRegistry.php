<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

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

    public function getFileHandler(string $fileName): FileHandler
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return $this->handlers[$extension] ?? $this->defaultHandler;
    }
}
