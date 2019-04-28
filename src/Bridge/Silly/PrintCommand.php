<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\Silly;

use LambdaPackager\Dependency\DependencyTreeBuilder;
use LambdaPackager\Tree\Node;
use LambdaPackager\Tree\RecursiveTreeIterator;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCommand
{
    public function __invoke(string $manifest, OutputInterface $output)
    {
        $root = DependencyTreeBuilder::buildFromManifestPath($manifest);
        $files = [];

        /** @var Node $dependency */
        foreach (new RecursiveTreeIterator($root) as $dependency) {
            $files[$dependency->getValue()] = true;
        }

        $output->writeln(array_keys($files));
    }
}
