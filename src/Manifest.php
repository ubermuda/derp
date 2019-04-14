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

        $this->include = array_map(function (string $fileName) {
            if (substr($fileName, 0, 1) !== '/') {
                $fileName = $this->projectRoot.'/'.$fileName;
            }

            return $fileName;
        }, $manifest['include']);

        $this->autoload = $manifest['autoload'];
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
}
