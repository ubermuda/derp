<?php

declare(strict_types=1);

namespace LambdaPackager\Autoload;

class ComposerAutoload
{
    /** @var string */
    private $projectRoot;

    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
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
