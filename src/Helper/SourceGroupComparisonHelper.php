<?php

namespace App\Helper;

use App\Entity\SourceGroupComparison;

class SourceGroupComparisonHelper
{
    private ComparisonHelper $comparisonHelper;

    /**
     * @param ComparisonHelper $comparisonHelper
     */
    public function __construct(ComparisonHelper $comparisonHelper)
    {
        $this->comparisonHelper = $comparisonHelper;
    }

    public function buildComparisons(SourceGroupComparison $sourceGroupComparison)
    {

    }
}