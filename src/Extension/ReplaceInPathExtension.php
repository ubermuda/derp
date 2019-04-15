<?php

declare(strict_types=1);

namespace LambdaPackager\Extension;

use RuntimeException;

class ReplaceInPathExtension implements Extension, ManifestAwareExtension
{
    use ManifestAwareExtensionTrait;

    /** @var string[] */
    private $replacements;

    public function __construct(array $replacements)
    {
        $this->replacements = $replacements;
    }

    public function beforeCopy(array $files): array
    {
        $replacements = $this->resolveReplacementsParameters($this->replacements);

        return array_map(function(string $fileName) use($replacements) {
            foreach ($replacements as $search => $replace) {
                if (false !== strpos($fileName, $search)) {
                    $fileName = str_replace($search, $replace, $fileName);
                }
            }

            return $fileName;
        }, $files);
    }

    private function resolveReplacementsParameters(array $replacements)
    {
        $resolvedReplacements = [];

        foreach ($replacements as $search => $replace) {
            $resolvedSearch = $this->resolveParameters($search);
            $resolvedReplace = $this->resolveParameters($replace);

            if ($resolvedSearch !== $resolvedReplace) {
                $resolvedReplacements[$resolvedSearch] = $resolvedReplace;
            }
        }

        return $resolvedReplacements;
    }

    private function resolveParameters(string $string)
    {
        if (null === $this->manifest) {
            throw new RuntimeException('No Manifest found.');
        }

        return strtr($string, [
            '%manifest.project_root%' => $this->manifest->getProjectRoot(),
        ]);
    }
}
