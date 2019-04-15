<?php

declare(strict_types=1);

namespace LambdaPackager;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

class Manifest implements IteratorAggregate
{
    /** @var string */
    private $projectRoot;

    /** @var string[] */
    private $include;

    /** @var string */
    private $autoload;

    public function __construct(string $path)
    {
        $this->projectRoot = dirname($path);

        $manifest = json_decode(file_get_contents($path), true);

        $this->autoload = $manifest['autoload'];
        $this->include = [];

        foreach ($manifest['include'] as $include) {
            $absolutePathInclude = $this->getAbsolutePath($include);
            $resolvedIncludes = $this->resolveGlob($absolutePathInclude);

            $this->include = array_merge($this->include, $resolvedIncludes);

        }
    }

    public function getProjectRoot(): string
    {
        return $this->projectRoot;
    }

    public function getAutoload(): string
    {
        return $this->autoload;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->include);
    }

    private function getAbsolutePath(string $fileName): string
    {
        if (substr($fileName, 0, 1) !== '/') {
            $fileName = $this->projectRoot.'/'.$fileName;
        }

        return $fileName;
    }

    private function resolveGlob(string $fileName): array
    {
        if (false === $resolvedFileNames = glob($fileName)) {
            throw new \RuntimeException(sprintf('Could not resolve glob expression "%s"', $fileName));
        }

        return $resolvedFileNames;
    }
}
