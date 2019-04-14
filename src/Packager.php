<?php

declare(strict_types=1);

namespace LambdaPackager;

use LambdaPackager\Autoload\ComposerAutoload;
use LambdaPackager\Extension\Extension;
use LambdaPackager\Extension\ManifestAwareExtension;
use LambdaPackager\FileHandler\FileHandlerRegistry;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class Packager
{
    /** @var Manifest */
    private $manifest;

    /** @var string */
    private $projectRoot;

    /** @var Extension */
    private $extension;

    /** @var string */
    private $buildDir;

    /** @var Filesystem */
    private $fs;

    /** @var FileHandlerRegistry */
    private $handlerRegistry;

    public function __construct(string $manifestPath, string $buildDir, Extension $extension)
    {
        $this->manifest = new Manifest(realpath($manifestPath));
        $this->projectRoot = $this->manifest->getProjectRoot();

        if (false === strpos($buildDir, '/')) {
            $buildDir = $this->projectRoot.'/'.$buildDir;
        }

        $this->buildDir = $buildDir;

        if ($extension instanceof ManifestAwareExtension) {
            $extension->setManifest($this->manifest);
        }

        $this->extension = $extension;
        $this->fs = new Filesystem();
        $this->handlerRegistry = new FileHandlerRegistry();
    }

    public function package()
    {
        if (file_exists($this->buildDir)) {
            $this->fs->remove($this->buildDir);
        }

        $this->fs->mkdir($this->buildDir);

        $files = [];

        foreach ($this->manifest as $fileName) {
            $handler = $this->handlerRegistry->getFileHandler($fileName);
            $files = array_merge($files, $handler->extractFileNames($fileName));
        }

        if ('composer' === $this->manifest->getAutoload()) {
            $files = array_merge($files, (new ComposerAutoload($this->projectRoot))->extractFileNames());
        }

        $files = $this->extension->beforeCopy($files);

        $this->copy($files);
    }

    private function copy(array $fileNames)
    {
        foreach ($fileNames as $absoluteFileName) {
            $relativePath = $this->getRelativePath($absoluteFileName);
            $relativeDirName = dirname($relativePath);

            $absoluteTargetDirName = $this->buildDir.'/'.$relativeDirName;
            $this->fs->mkdir($absoluteTargetDirName);

            $absoluteTargetFileName = $this->buildDir.'/'.$relativePath;
            $this->fs->copy($absoluteFileName, $absoluteTargetFileName);
        }
    }

    private function getRelativePath(string $absolutePath): string
    {
        if (substr($absolutePath, 0, 1) !== '/') {
            throw new RuntimeException(sprintf('Cannot determine relative project path from a relative path "%s"', $absolutePath));
        }

        if (substr_count($absolutePath, $this->projectRoot) !== 1) {
            throw new RuntimeException(sprintf('File does not seem to be inside current project (file: "%s", project: "%s"', $absolutePath, $this->projectRoot));
        }

        return ltrim(str_replace($this->projectRoot, '', $absolutePath), '/');
    }
}
