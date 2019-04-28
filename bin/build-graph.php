#!/usr/bin/php
<?php

declare(strict_types=1);

use Alom\Graphviz\Digraph;
use LambdaPackager\Tree\Node;
use LambdaPackager\Dependency\DependencyTreeBuilder;
use LambdaPackager\Manifest;

require_once __DIR__ . '/../vendor/autoload.php';

$manifestPath = $argv[1] ?? __DIR__ . '/../manifest.json';

$builder = new DependencyTreeBuilder(new Manifest($manifestPath));
$root = $builder->build();

$graph = new class
{
    private $graph;

    public function __construct()
    {
        $this->graph = new Digraph('G');
    }

    public function build(Node $dependency)
    {
        $this->processDependency($dependency);

        return $this->graph->render();
    }

    private function processDependency(Node $dependency): void
    {
        /** @var Node $child */
        foreach ($dependency as $child) {
            if (in_array(basename($dependency->getValue()), ['Php7.php', 'Php5.php'])) {
                continue;
            }

            $this->graph->edge([basename($dependency->getValue()), basename($child->getValue())]);
            $this->processDependency($child);
        }
    }
};

echo $graph->build($root);
die;
$graph = new Digraph('G');
$graph
//    ->node('foo')
//    ->node('bar')
    ->edge(['foo', 'bar']);

echo $graph->render();
