#!/usr/bin/env php
<?php

declare(strict_types=1);

use LambdaPackager\Tree\Node;
use LambdaPackager\Dependency\DependencyTreeBuilder;
use LambdaPackager\Extension\ExtensionCollection;
use LambdaPackager\Extension\ReplaceInPathExtension;
use LambdaPackager\Manifest;
use LambdaPackager\Packager;
use LambdaPackager\Tree\RecursiveTreeIterator;
use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app->command('print manifest', function(string $manifest, OutputInterface $output) {
    $root = DependencyTreeBuilder::buildFromManifestPath($manifest);
    $files = [];

    /** @var Node $dependency */
    foreach (new RecursiveTreeIterator($root) as $dependency) {
        $files[$dependency->getValue()] = true;
    }

    $output->writeln(array_keys($files));
});

$app->command('why manifest pattern [--absolute]', function(string $manifest, string $pattern, bool $absolute, SymfonyStyle $io) {
    $manifest = new Manifest($manifest);
    $root = (new DependencyTreeBuilder($manifest))->build();

    $deps = $root->filterChildren(function(Node $node) use ($pattern) {
        return fnmatch($node->getValue(), $pattern);
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
});

$app->command('package manifest target', function (string $manifest, string $target, OutputInterface $output) {
    $extension = new ExtensionCollection([
        new ReplaceInPathExtension([realpath(__DIR__.'/..') => '%manifest.project_root%'])
    ]);

    $packager = new Packager($manifest, $target, $extension);
    $packager->package();

    $output->writeln(sprintf('Packaged application in "%s"', $target));
});

$app->run();
