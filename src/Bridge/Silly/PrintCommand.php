<?php

declare(strict_types=1);

namespace Derp\Bridge\Silly;

use Derp\Dependency\DependencyTreeBuilder;
use Derp\Tree\Node;
use Derp\Tree\RecursiveTreeIterator;
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
