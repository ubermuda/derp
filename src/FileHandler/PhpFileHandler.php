<?php

declare(strict_types=1);

namespace LambdaPackager\FileHandler;

use LambdaPackager\Bridge\PhpParser\FilesFinderVisitor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

class PhpFileHandler implements FileHandler
{
    /** @var \PhpParser\Parser */
    private $parser;

    /** @var FilesFinderVisitor */
    private $visitor;

    /** @var NodeTraverser */
    private $traverser;

    /** @var string[] */
    private $files = [];

    /** @var string[] */
    private $currentlyProcessingFileNames = [];

    public function __construct()
    {
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->visitor = new FilesFinderVisitor();

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor($this->visitor);

        $this->traverser = $traverser;
    }

    public function extractFileNames(string $fileName): array
    {
        $this->files[] = $fileName;
        $this->currentlyProcessingFileNames[] = $fileName;

        $this->processFile($fileName);

        $this->files = array_unique($this->files);
        sort($this->files);

        return $this->files;
    }

    private function processFile(string $fileName): void
    {
        $stmts = $this->parser->parse(file_get_contents($fileName));

        $this->traverser->traverse($stmts);

        foreach ($this->visitor->all() as $foundFileName) {
            if ($this->fileNeedsProcessing($foundFileName)) {
                array_push($this->currentlyProcessingFileNames, $foundFileName);
                $this->processFile($foundFileName);
            }

            if (count($this->currentlyProcessingFileNames) > 0) {
                array_push($this->files, array_pop($this->currentlyProcessingFileNames));
            }
        }
    }

    private function fileNeedsProcessing(string $fileName): bool
    {
        return
               false === array_search($fileName, $this->files)
            && false === array_search($fileName, $this->currentlyProcessingFileNames);
    }
}
