#!/usr/bin/php
<?php

declare(strict_types=1);

use LambdaPackager\DependencyTreeBuilder;
use LambdaPackager\Manifest;

require_once __DIR__ . '/../vendor/autoload.php';

$manifestPath = $argv[1] ?? __DIR__ . '/../manifest.json';

$builder = new DependencyTreeBuilder(new Manifest($manifestPath));
$builder->build();
