<?php

declare(strict_types=1);

namespace Tests\Unit\Factory\Capture;

use App\Entity\Capture\Snapshot;
use App\Entity\Capture\Source;
use App\Factory\Capture\SnapshotFactory;
use Codeception\Test\Unit;

class SnapshotFactoryTest extends Unit
{
    public function testCreate(): void
    {
        $factory = new SnapshotFactory();
        $source = $this->makeEmpty(Source::class);
        $snapshot = $factory->create($source);
        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertSame($source, $snapshot->getSource());
    }
}
