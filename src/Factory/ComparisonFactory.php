<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Comparison;
use App\Entity\Snapshot;

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
