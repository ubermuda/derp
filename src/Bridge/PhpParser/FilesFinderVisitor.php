<?php

namespace LambdaPackager\Bridge\PhpParser;

use LambdaPackager\Bridge\PhpParser\Strategy;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FilesFinderVisitor extends NodeVisitorAbstract
{
    /** @var Strategy\Strategy[] */
    private $strategies = [];

    /** @var string[] */
    private $files = [];

    public function __construct()
    {
        $this->strategies = [
            new Strategy\ClassConstantStrategy(),
            new Strategy\ExtendsStrategy(),
            new Strategy\FunctionCallStrategy(),
            new Strategy\ImplementsStrategy(),
            new Strategy\NewClassStrategy(),
            new Strategy\StaticCallStrategy(),
            new Strategy\UseStrategy(),
        ];
    }

    public function all(): array
    {
        return $this->files;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->files = [];
    }

    public function afterTraverse(array $nodes)
    {
        $this->files = array_unique($this->files);
    }

    public function enterNode(Node $node)
    {
        /**
         * @todo handle the following use-cases:
         *
         * - FQCN in docblocks
         * - include/require calls (though we'll certainly not be able to resolve filenames from this)
         * - arbitrary files (eg. filenames passed as argument of something)
         */

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($node)) {
                $this->files = array_merge($this->files, $strategy->extractFileNames($node));
            }
        }
    }
}
