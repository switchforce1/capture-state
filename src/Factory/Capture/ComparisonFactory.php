<?php
declare(strict_types=1);

namespace App\Factory\Capture;

use App\Entity\Capture\Comparison;
use App\Entity\Capture\Snapshot;

class ComparisonFactory
{
    public function create(Snapshot $snapshot1, Snapshot $snapshot2): Comparison
    {
        $comparison =  new Comparison();
        $comparison
            ->setSnapshot1($snapshot1)
            ->setSnapshot2($snapshot2)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;

        return $comparison;
    }
}
