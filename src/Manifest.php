<?php

declare(strict_types=1);

namespace Derp;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use Derp\Autoload\Autoload;
use Derp\Autoload\AutoloadFactory;

class Manifest implements IteratorAggregate
{
    /** @var string */
    private $baseName;

    /** @var string */
    private $projectRoot;

    /** @var string[] */
    private $include;

    /** @var string[] */
    private $excludeClass;

    /** @var string */
    private $autoload;

    public function __construct(string $path)
    {
        $path = realpath($path);

        $this->baseName = basename($path);
        $this->projectRoot = dirname($path);

        $manifest = json_decode(file_get_contents($path), true);

        $this->autoload = $manifest['autoload'];
        $this->include = [];

        foreach ($manifest['include'] as $include) {
            $absolutePathInclude = $this->getAbsolutePath($include);
            $resolvedIncludes = $this->resolveGlob($absolutePathInclude);

            $this->include = array_merge($this->include, $resolvedIncludes);
        }

        $this->excludeClass = [];

        if (isset($manifest['exclude-class'])) {
            $this->excludeClass = $manifest['exclude-class'];
        }
    }

    public function getManifestPath(): string
    {
        return $this->projectRoot.'/'.$this->baseName;
    }

    public function getProjectRoot(): string
    {
        return $this->projectRoot;
    }

    public function getAutoload(): string
    {
        return $this->autoload;
    }

    public function getAutoloadManager(): Autoload
    {
        return (new AutoloadFactory())->createForManifest($this);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->include);
    }

    private function getAbsolutePath(string $fileName): string
    {
        if ('/' !== substr($fileName, 0, 1)) {
            $fileName = $this->projectRoot.'/'.$fileName;
        }

        return $fileName;
    }

    public function hasExcludeClass(string $className): bool
    {
        foreach ($this->excludeClass as $pattern) {
            if (fnmatch($pattern, $className, FNM_NOESCAPE)) {
                return true;
            }
        }

        return false;
    }

    private function resolveGlob(string $fileName): array
    {
        if (false === $resolvedFileNames = glob($fileName)) {
            throw new \RuntimeException(sprintf('Could not resolve glob expression "%s"', $fileName));
        }

        return $resolvedFileNames;
    }
}
