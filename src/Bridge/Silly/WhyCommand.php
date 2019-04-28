<?php

declare(strict_types=1);

namespace LambdaPackager\Bridge\Silly;

use LambdaPackager\Dependency\DependencyTreeBuilder;
use LambdaPackager\Manifest;
use LambdaPackager\Tree\Node;
use Symfony\Component\Console\Style\SymfonyStyle;

class WhyCommand
{
    public function __invoke(string $manifest, string $pattern, bool $absolute, SymfonyStyle $io)
    {
        $manifest = new Manifest($manifest);
        $root = (new DependencyTreeBuilder($manifest))->build();

        $deps = $root->filterChildren(function(Node $node) use ($pattern) {
            return fnmatch($pattern, $node->getValue());
        });

        if (count($deps) === 0) {
            $io->error('No occurrences found.');

            return 1;
        }

        $io->title(sprintf('Found %d occurrences:', count($deps)));

        foreach ($deps as $dep) {
            $path = [$dep->getValue()];

            while (!$dep->isRoot()) {
                $dep = $dep->getParent();
                $path[] = $dep->getValue();
            }

            if (!$absolute) {
                $path = array_map(function (string $path) use ($manifest) {
                    return str_replace($manifest->getProjectRoot().'/', '', $path);
                }, $path);
            }

            $io->listing(array_reverse($path));
        }
    }
}
