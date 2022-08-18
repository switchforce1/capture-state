<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ComparisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComparisonRepository::class)]
class Comparison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reason;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Source::class, inversedBy: 'comparisons')]
    #[ORM\JoinColumn(nullable: false)]
    private $source;

    #[ORM\ManyToOne(targetEntity: Snapshot::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $snapshot1;

    #[ORM\ManyToOne(targetEntity: Snapshot::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $snapshot2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSnapshot1(): ?Snapshot
    {
        return $this->snapshot1;
    }

    public function setSnapshot1(?Snapshot $snapshot1): self
    {
        $this->snapshot1 = $snapshot1;

        return $this;
    }

    public function getSnapshot2(): ?Snapshot
    {
        return $this->snapshot2;
    }

    public function setSnapshot2(?Snapshot $snapshot2): self
    {
        $this->snapshot2 = $snapshot2;

        return $this;
    }
}
