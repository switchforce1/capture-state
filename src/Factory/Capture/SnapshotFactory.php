<?php
declare(strict_types=1);

namespace App\Factory\Capture;

use App\Entity\Capture\Snapshot;
use App\Entity\Capture\Source;

class SnapshotFactory
{
    public function create(Source $source): Snapshot
    {
        $snapshot = new Snapshot();
        $snapshot->setSource($source);
        return $snapshot;
    }
}