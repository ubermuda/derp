<?php

declare(strict_types=1);

namespace Derp\FileHandler;

use Derp\Dependency\FileDependency;
use Derp\Manifest;

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
