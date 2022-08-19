<?php
declare(strict_types=1);

namespace App\Helper;

use App\Entity\Comparison;

class ComparisonHelper
{
    public function refreshComparisonData(Comparison $comparison): Comparison
    {
        if (empty($comparison->getSnapshot1()) || empty($comparison->getSnapshot2())) {
            throw new \Exception('Comparison need at least 2 snapshot');
        }

        $snapshot1Data = $comparison->getSnapshot1()->getData();
        $snapshot2Data = $comparison->getSnapshot2()->getData();
        $result1 = array_diff(array_map('serialize', $snapshot2Data), array_map('serialize', $snapshot1Data));
        $multidimensionalResult1 = array_map('unserialize', $result1);

        $result2 = array_diff(array_map('serialize', $snapshot1Data), array_map('serialize', $snapshot2Data));
        $multidimensionalResult2 = array_map('unserialize', $result2);


        $comparison->setMainData(json_encode($multidimensionalResult1));
        $comparison->setRevertData(json_encode($multidimensionalResult2));

        return $comparison;
    }
}
