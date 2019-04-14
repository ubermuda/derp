<?php

namespace LambdaPackager;

use LambdaPackager\Strategy\ClassConstantStrategy;
use LambdaPackager\Strategy\ExtendsStrategy;
use LambdaPackager\Strategy\FunctionCallStrategy;
use LambdaPackager\Strategy\ImplementsStrategy;
use LambdaPackager\Strategy\NewClassStrategy;
use LambdaPackager\Strategy\StaticCallStrategy;
use LambdaPackager\Strategy\Strategy;
use LambdaPackager\Strategy\UseStrategy;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FilesFinderVisitor extends NodeVisitorAbstract
{
    /** @var Strategy[] */
    private $strategies = [];

    /** @var string[] */
    private $files = [];

    public function __construct()
    {
        $this->strategies = [
            new ClassConstantStrategy(),
            new ExtendsStrategy(),
            new FunctionCallStrategy(),
            new ImplementsStrategy(),
            new NewClassStrategy(),
            new StaticCallStrategy(),
            new UseStrategy(),
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
