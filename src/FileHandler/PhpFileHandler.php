<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Bridge\PhpParser\CouldNotProcessNodeException;
use LambdaPackager\Bridge\PhpParser\FilesFinderVisitor;
use LambdaPackager\ClassDependency;
use LambdaPackager\Dependency;
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

    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->visitor = new FilesFinderVisitor();

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor($this->visitor);

        $this->traverser = $traverser;
    }

    public function extractDependencies(string $fileName): array
    {
        $stmts = $this->parser->parse(file_get_contents($fileName));

        try {
            $this->traverser->traverse($stmts);
        } catch (CouldNotProcessNodeException $e) {
            throw new RuntimeException(sprintf('Error while processing node in "%s" at line %d', $fileName, $e->getNode()->getStartLine()), 0, $e);
        }

        return $this->getUniqueDependencies($this->visitor->all());
    }

    /**
     * @param Dependency[] $dependencies
     */
    private function getUniqueDependencies(array $dependencies): array
    {
        $uniqueDependencies = [];

        foreach ($dependencies as $dependency) {
            $uniqueDependencies[$dependency->getFilePath()] = $dependency;
        }

        return array_values($uniqueDependencies);
    }
}
