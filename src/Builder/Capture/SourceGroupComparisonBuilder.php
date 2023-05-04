<?php

declare(strict_types=1);

namespace App\Builder\Capture;

class SourceGroupComparisonBuilder
{
    private ComparisonBuilder $comparisonBuilder;

    /**
     * @param ComparisonBuilder $comparisonBuilder
     */
    public function __construct(ComparisonBuilder $comparisonBuilder)
    {
        $this->comparisonBuilder = $comparisonBuilder;
    }
}
