<?php

namespace App\Builder;

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