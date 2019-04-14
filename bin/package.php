<?php

declare(strict_types=1);

use LambdaPackager\Packager;

require_once __DIR__ . '/../vendor/autoload.php';

$manifestPath = $argv[1] ?? __DIR__ . '/../manifest.json';
$buildDir = $argv[2] ?? __DIR__ . '/../build';
$collisions = [realpath(__DIR__.'/..')];

$packager = new Packager($manifestPath, $buildDir, $collisions);
$packager->package();
