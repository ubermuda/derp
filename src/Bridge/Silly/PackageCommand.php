<?php

declare(strict_types=1);

namespace Derp\Bridge\Silly;

use Derp\Extension\Collection;
use Derp\Extension\ReplaceInPath;
use Derp\Packager;
use Symfony\Component\Console\Output\OutputInterface;

class PackageCommand
{
    public function __invoke(string $manifest, string $target, OutputInterface $output)
    {
        $extension = new Collection([
            new ReplaceInPath([realpath(__DIR__.'/../../..') => '%manifest.project_root%']),
        ]);

        $packager = new Packager($manifest, $target, $extension);
        $packager->package();

        $output->writeln(sprintf('Packaged application in "%s"', $target));
    }
}
