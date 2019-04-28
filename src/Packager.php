<?php

declare(strict_types=1);

namespace LambdaPackager;

use LambdaPackager\Autoload\AutoloadFactory;
use LambdaPackager\Dependency\DependencyTreeBuilder;
use LambdaPackager\Dependency\FileDependency;
use LambdaPackager\Extension\Extension;
use LambdaPackager\Tree\RecursiveTreeIterator;
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

    public function __construct(string $manifestPath, string $buildDir, Extension $extension)
    {
        $this->manifest = new Manifest(realpath($manifestPath));
        $this->projectRoot = $this->manifest->getProjectRoot();

        if (false === strpos($buildDir, '/')) {
            $buildDir = $this->projectRoot.'/'.$buildDir;
        }

        $this->buildDir = $buildDir;

        if ($extension instanceof ManifestAware) {
            $extension->setManifest($this->manifest);
        }

        $this->extension = $extension;
        $this->fs = new Filesystem();
    }

    public function package(): void
    {
        if (file_exists($this->buildDir)) {
            $this->fs->remove($this->buildDir);
        }

        $this->fs->mkdir($this->buildDir);

        $autoload = (new AutoloadFactory())->createForManifest($this->manifest);
        $autoload->initialize();

        $root = (new DependencyTreeBuilder($this->manifest))->build();
        $files = [];

        /** @var FileDependency $dependency */
        foreach (new RecursiveTreeIterator($root) as $dependency) {
            $files[$dependency->getFilePath()] = true;
        }

        unset($files[$this->manifest->getManifestPath()]);

        $files = array_keys($files);
        $files = $this->extension->beforeCopy($files);

        $this->copy($files);
    }

    private function copy(array $fileNames): void
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
        if ('/' !== substr($absolutePath, 0, 1)) {
            throw new RuntimeException(sprintf('Cannot determine relative project path from a relative path "%s"', $absolutePath));
        }

        if (1 !== substr_count($absolutePath, $this->projectRoot)) {
            throw new RuntimeException(sprintf('File does not seem to be inside current project (file: "%s", project: "%s"', $absolutePath, $this->projectRoot));
        }

        return ltrim(str_replace($this->projectRoot, '', $absolutePath), '/');
    }
}
