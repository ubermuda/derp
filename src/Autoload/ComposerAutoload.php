<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

use LambdaPackager\Manifest;

class ComposerAutoload implements Autoload
{
    /** @var string */
    private $projectRoot;

    public function __construct(Manifest $manifest)
    {
        $this->projectRoot = $manifest->getProjectRoot();
    }

    public function initialize(): void
    {
        require_once $this->projectRoot.'/vendor/autoload.php';
    }

    public function extractFileNames(): array
    {
        $files = [$this->projectRoot.'/vendor/autoload.php'];

        $files = array_merge($files, glob($this->projectRoot.'/vendor/composer/*.php'));

        if (file_exists($autoloadFiles = $this->projectRoot.'/vendor/composer/autoload_files.php')) {
            $files = array_merge($files, array_values(include($autoloadFiles)));
        }

        return $files;
    }
}
