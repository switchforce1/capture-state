<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Snapshot;
use App\Entity\Source;

class SnapshotFactory
{
    public function create(Source $source): Snapshot
    {
        $snapshot = new Snapshot();
        $snapshot->setSource($source);
        return $snapshot;
    }
}