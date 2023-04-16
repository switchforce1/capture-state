<?php
declare(strict_types=1);

namespace App\Builder;

use App\Entity\Comparison;

class ComparisonBuilder
{
    private const MAX_COMPARE_LEVEL = 11;
    public function refreshComparisonData(Comparison $comparison): Comparison
    {
        if (empty($comparison->getSnapshot1()) || empty($comparison->getSnapshot2())) {
            throw new \Exception('Comparison need at least 2 snapshot');
        }

        $snapshot1Data = $comparison->getSnapshot1()->getData();
        $snapshot2Data = $comparison->getSnapshot2()->getData();

        $comparison->setMainData(json_encode($this->compareArrays($snapshot1Data, $snapshot2Data)));
        $comparison->setRevertData(json_encode($this->compareArrays($snapshot2Data, $snapshot1Data)));

        return $comparison;
    }

    private function compareArrays(array $firstArray, array $secondArray, bool $reverseKey = false, int $level = 1): array
    {
        if ($level > self::MAX_COMPARE_LEVEL) {
            return [];
        }
        $oldKey = 'old';
        $newKey = 'new';
        if ($reverseKey) {
            $oldKey = 'new';
            $newKey = 'old';
        }
        $difference = [];
        foreach ($firstArray as $firstKey => $firstValue) {
            if (is_array($firstValue)) {
                if (!array_key_exists($firstKey, $secondArray) || !is_array($secondArray[$firstKey])) {
                    $difference[$oldKey][$firstKey] = $firstValue;
                    $difference[$newKey][$firstKey] = '';
                } else {
                    $newDiff = $this->compareArrays($firstValue, $secondArray[$firstKey], $reverseKey, ++$level);
                    if (!empty($newDiff)) {
                        $difference[$oldKey][$firstKey] = $newDiff[$oldKey];
                        $difference[$newKey][$firstKey] = $newDiff[$newKey];
                    }
                }
            } else {
                if (!array_key_exists($firstKey, $secondArray) || $secondArray[$firstKey] != $firstValue) {
                    $difference[$oldKey][$firstKey] = $firstValue;
                    $difference[$newKey][$firstKey] = $secondArray[$firstKey] ?? null;
                }
            }
        }
        return $difference;
    }
}
