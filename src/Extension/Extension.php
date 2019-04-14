<?php

declare(strict_types=1);

namespace LambdaPackager\Extension;

interface Extension
{
    public function beforeCopy(array $files): array;
}
