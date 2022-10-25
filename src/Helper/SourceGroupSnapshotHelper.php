<?php

namespace App\Helper;

use App\Entity\Snapshot;
use App\Entity\Source;
use App\Entity\SourceGroupComparison;
use App\Entity\SourceGroupSnapshot;
use App\Repository\SnapshotRepository;
use Doctrine\ORM\EntityManagerInterface;

class SourceGroupSnapshotHelper
{
    private EntityManagerInterface $entityManager;
    private SnapshotRepository $snapshotRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SnapshotRepository $snapshotRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SnapshotRepository $snapshotRepository)
    {
        $this->entityManager = $entityManager;
        $this->snapshotRepository = $snapshotRepository;
    }

    public function getFormattedSourceSnapshots(SourceGroupSnapshot $sourceGroupSnapshot): array
    {
        $sourceGroup = $sourceGroupSnapshot->getSourceGroup();
        if (empty($sourceGroup)) {
            return [];
        }
        $sources = $sourceGroup->getSources();
        if (empty($sources)) {
            return [];
        }

        $formattedSourceSnapshots = [];
        /** @var Source $source */
        foreach ($sources as $source) {
            $snapshot = $this->snapshotRepository->findOneBy([
                'source' => $source,
                'sourceGroupSnapshot' => $sourceGroupSnapshot
            ]);
            if (!empty($snapshot)) {
                $formattedSourceSnapshots[$source->getId()] =  $snapshot;
            }
        }

        return $formattedSourceSnapshots;
    }
}