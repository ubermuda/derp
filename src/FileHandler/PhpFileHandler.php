<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Bridge\PhpParser\CouldNotProcessNodeException;
use LambdaPackager\Bridge\PhpParser\FilesFinderVisitor;
use LambdaPackager\Tree\Node;
use LambdaPackager\Manifest;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use RuntimeException;

class PhpFileHandler implements FileHandler
{
    /** @var Parser */
    private $parser;

    /** @var FilesFinderVisitor */
    private $visitor;

    /** @var NodeTraverser */
    private $traverser;

    /** @var Manifest */
    private $manifest;

    public function __construct(Manifest $manifest)
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->visitor = new FilesFinderVisitor($manifest);

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor($this->visitor);

        $this->traverser = $traverser;
        $this->manifest = $manifest;
    }

    public function extractDependencies(Node $dependency): array
    {
        $stmts = $this->parser->parse(file_get_contents($dependency->getValue()));

        try {
            $this->traverser->traverse($stmts);
        } catch (CouldNotProcessNodeException $e) {
            throw new RuntimeException(sprintf('Error while processing node in "%s" at line %d', $dependency->getValue(), $e->getNode()->getStartLine()), 0, $e);
        }

        return $this->getUniqueDependencies($this->visitor->all());
    }

    /**
     * @param Node[] $dependencies
     */
    private function getUniqueDependencies(array $dependencies): array
    {
        $uniqueDependencies = [];

        foreach ($dependencies as $dependency) {
            $uniqueDependencies[$dependency->getValue()] = $dependency;
        }

        return array_values($uniqueDependencies);
    }
}
