<?php

declare(strict_types=1);

namespace Derp\Extension;

interface Extension
{
    public function beforeCopy(array $files): array;
}
