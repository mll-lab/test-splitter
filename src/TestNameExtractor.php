<?php

declare(strict_types=1);

namespace DaveLiddament\TestSplitter;

class TestNameExtractor
{
    /**
     * @return array<int,string>
     */
    public function getDeDupedTestClassNames(string $input): array
    {
        $testNames = [];
        foreach (explode(\PHP_EOL, $input) as $line) {
            $listPosition = strpos($line, ' - ');
            if (false === $listPosition) {
                continue;
            }

            $testNames[] = substr($line, 0, $listPosition);
        }

        return $testNames;
    }
}
