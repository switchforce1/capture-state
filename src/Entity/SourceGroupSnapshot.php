<?php

namespace App\Entity;

use App\Repository\SourceGroupSnapshotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SourceGroupSnapshotRepository::class)]
class SourceGroupSnapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $code;

    #[ORM\OneToMany(mappedBy: 'sourceGroupSnapshot', targetEntity: SourceGroupComparison::class, orphanRemoval: true)]
    private $sourceGroupComparisons;

    #[ORM\ManyToOne(targetEntity: SourceGroup::class, inversedBy: 'sourceGroupSnapshots')]
    #[ORM\JoinColumn(nullable: false)]
    private $sourceGroup;

    #[ORM\OneToMany(mappedBy: 'sourceGroupSnapshot', targetEntity: Snapshot::class)]
    private $snapshots;

    public function __construct()
    {
        $this->sourceGroupComparisons = new ArrayCollection();
        $this->code = (string) Uuid::v4();
        $this->snapshots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, SourceGroupComparison>
     */
    public function getSourceGroupComparisons(): Collection
    {
        return $this->sourceGroupComparisons;
    }

    public function addSourceGroupComparison(SourceGroupComparison $sourceGroupComparison): self
    {
        if (!$this->sourceGroupComparisons->contains($sourceGroupComparison)) {
            $this->sourceGroupComparisons[] = $sourceGroupComparison;
            $sourceGroupComparison->setSourceGroupSnapshot($this);
        }

        return $this;
    }

    public function removeSourceGroupComparison(SourceGroupComparison $sourceGroupComparison): self
    {
        if ($this->sourceGroupComparisons->removeElement($sourceGroupComparison)) {
            // set the owning side to null (unless already changed)
            if ($sourceGroupComparison->getSourceGroupSnapshot() === $this) {
                $sourceGroupComparison->setSourceGroupSnapshot(null);
            }
        }

        return $this;
    }

    public function getSourceGroup(): ?SourceGroup
    {
        return $this->sourceGroup;
    }

    public function setSourceGroup(?SourceGroup $sourceGroup): self
    {
        $this->sourceGroup = $sourceGroup;

        return $this;
    }

    /**
     * @return Collection<int, Snapshot>
     */
    public function getSnapshots(): Collection
    {
        return $this->snapshots;
    }

    public function addSnapshot(Snapshot $snapshot): self
    {
        if (!$this->snapshots->contains($snapshot)) {
            $this->snapshots[] = $snapshot;
            $snapshot->setSourceGroupSnapshot($this);
        }

        return $this;
    }

    public function removeSnapshot(Snapshot $snapshot): self
    {
        if ($this->snapshots->removeElement($snapshot)) {
            // set the owning side to null (unless already changed)
            if ($snapshot->getSourceGroupSnapshot() === $this) {
                $snapshot->setSourceGroupSnapshot(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getSourceGroup()->getName() . ': => ' . $this->getName(). ' -> ' . $this->getCode();
    }
}
