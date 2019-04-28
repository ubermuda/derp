<?php

namespace LambdaPackager\Bridge\PhpParser;

use LambdaPackager\Bridge\PhpParser\Strategy;
use LambdaPackager\Dependency\FileDependency;
use LambdaPackager\Manifest;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FilesFinderVisitor extends NodeVisitorAbstract
{
    private const AUTOLOAD_FAIL_STRATEGY_SKIP = 'skip';

    /** @var Strategy\Strategy[] */
    private $strategies = [];

    /** @var FileDependency[] */
    private $dependencies = [];

    /** @var string */
    private $autoloadFailStrategy;

    public function __construct(Manifest $manifest, $autoloadFailStrategy = self::AUTOLOAD_FAIL_STRATEGY_SKIP)
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

        foreach ($this->strategies as $strategy) {
            if ($strategy instanceof Strategy\ManifestAwareStrategy) {
                $strategy->setManifest($manifest);
            }
        }

        $this->autoloadFailStrategy = $autoloadFailStrategy;
    }

    public function all(): array
    {
        return $this->dependencies;
    }

    public function has(FileDependency $dependency): bool
    {
        foreach ($this->dependencies as $existingDependency) {
            if ($existingDependency->getFilePath() === $dependency->getFilePath()) {
                return true;
            }
        }

        return false;
    }

    public function add(FileDependency $dependency): self
    {
        $this->dependencies[] = $dependency;

        return $this;
    }

    /** @var FileDependency[] $dependencies */
    public function addAll(array $dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $this->add($dependency);
        }

        return $this;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->dependencies = [];
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
            try {
                if ($strategy->supports($node)) {
                    $this->addAll($strategy->extractDependencies($node));
                }
            } catch (CouldNotAutoloadClassException $e) {
                if ($this->autoloadFailStrategy === self::AUTOLOAD_FAIL_STRATEGY_SKIP) {
                    continue;
                }

                throw new CouldNotProcessNodeException($node, $e);
            }
        }
    }
}
