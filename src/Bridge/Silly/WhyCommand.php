<?php

declare(strict_types=1);

namespace Derp\Bridge\Silly;

use Derp\Dependency\DependencyTreeBuilder;
use Derp\Manifest;
use Derp\Tree\Node;
use Symfony\Component\Console\Style\SymfonyStyle;

class WhyCommand
{
    public function __invoke(string $manifest, string $pattern, bool $absolute, SymfonyStyle $io)
    {
        $manifest = new Manifest($manifest);
        $root = (new DependencyTreeBuilder($manifest))->build();

        $deps = $root->filterChildren(function (Node $node) use ($pattern) {
            return fnmatch($pattern, $node->getValue());
        });

        if (0 === count($deps)) {
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
