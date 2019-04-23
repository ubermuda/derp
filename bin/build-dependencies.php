#!/usr/bin/php
<?php

declare(strict_types=1);

use LambdaPackager\Dependency;
use LambdaPackager\DependencyTreeBuilder;
use LambdaPackager\Manifest;
use LambdaPackager\RecursiveDependencyIterator;

require_once __DIR__ . '/../vendor/autoload.php';

$manifestPath = $argv[1] ?? __DIR__ . '/../manifest.json';

$builder = new DependencyTreeBuilder(new Manifest($manifestPath));
$root = $builder->build();

$files = [];

/** @var Dependency $dependency */
foreach (new RecursiveDependencyIterator($root) as $dependency) {
    $files[$dependency->getFilePath()] = true;
}

$files = array_keys($files);
sort($files);

dump($files);
