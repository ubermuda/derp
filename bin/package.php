#!/usr/bin/php
<?php

declare(strict_types=1);

use LambdaPackager\Extension\ExtensionCollection;
use LambdaPackager\Extension\ReplaceInPathExtension;
use LambdaPackager\Packager;

require_once __DIR__ . '/../vendor/autoload.php';

$manifestPath = $argv[1] ?? __DIR__ . '/../manifest.json';
$buildDir = $argv[2] ?? __DIR__ . '/../build';

$extension = new ExtensionCollection([
    new ReplaceInPathExtension([realpath(__DIR__.'/..') => '%manifest.project_root%'])
]);

$packager = new Packager($manifestPath, $buildDir, $extension);
$packager->package();
