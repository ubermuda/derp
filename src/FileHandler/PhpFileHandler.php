<?php

declare(strict_types=1);

namespace Derp\FileHandler;

use Derp\Bridge\PhpParser\CouldNotProcessNodeException;
use Derp\Bridge\PhpParser\FilesFinderVisitor;
use Derp\Dependency\FileDependency;
use Derp\Manifest;
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

    public function extractDependencies(FileDependency $dependency): array
    {
        $stmts = $this->parser->parse(file_get_contents($dependency->getFilePath()));

        try {
            $this->traverser->traverse($stmts);
        } catch (CouldNotProcessNodeException $e) {
            throw new RuntimeException(sprintf('Error while processing node in "%s" at line %d', $dependency->getFilePath(), $e->getNode()->getStartLine()), 0, $e);
        }

        return $this->getUniqueDependencies($this->visitor->all());
    }

    /**
     * @param FileDependency[] $dependencies
     *
     * @return FileDependency[]
     */
    private function getUniqueDependencies(array $dependencies): array
    {
        $uniqueDependencies = [];

        foreach ($dependencies as $dependency) {
            $uniqueDependencies[$dependency->getValue()] = $dependency;
        }

        return array_values($uniqueDependencies);
    }

    public function supports(FileDependency $dependency): bool
    {
        $extension = pathinfo($dependency->getValue(), PATHINFO_EXTENSION);

        return $extension === 'php' || false !== strpos(file_get_contents($dependency->getFilePath()), '<?php');

    }
}
