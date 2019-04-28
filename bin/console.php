#!/usr/bin/env php
<?php

declare(strict_types=1);

use LambdaPackager\Bridge\Silly;
use Silly\Application;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app->command('print manifest', new Silly\PrintCommand());
$app->command('why manifest pattern [--absolute]', new Silly\WhyCommand());
$app->command('package manifest target', new Silly\PackageCommand());

$app->run();
