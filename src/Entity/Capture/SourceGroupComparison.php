<?php

declare(strict_types=1);

namespace App\Entity\Capture;

use App\Repository\Capture\SourceGroupComparisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SourceGroupComparisonRepository::class)]
class SourceGroupComparison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reason;

    #[ORM\Column(type: 'string', length: 255)]
    private $code;

    #[ORM\ManyToOne(targetEntity: SourceGroupSnapshot::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $sourceGroupSnapshot1;

    #[ORM\ManyToOne(targetEntity: SourceGroupSnapshot::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $sourceGroupSnapshot2;

    public function __construct()
    {
        $this->code = Uuid::v4()->toRfc4122();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

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

    public function getSourceGroupSnapshot(): ?SourceGroupSnapshot
    {
        return $this->sourceGroupSnapshot;
    }

    public function setSourceGroupSnapshot(?SourceGroupSnapshot $sourceGroupSnapshot): self
    {
        $this->sourceGroupSnapshot = $sourceGroupSnapshot;

        return $this;
    }

    public function getSourceGroupSnapshot1(): ?SourceGroupSnapshot
    {
        return $this->sourceGroupSnapshot1;
    }

    public function setSourceGroupSnapshot1(?SourceGroupSnapshot $sourceGroupSnapshot1): self
    {
        $this->sourceGroupSnapshot1 = $sourceGroupSnapshot1;

        return $this;
    }

    public function getSourceGroupSnapshot2(): ?SourceGroupSnapshot
    {
        return $this->sourceGroupSnapshot2;
    }

    public function setSourceGroupSnapshot2(?SourceGroupSnapshot $sourceGroupSnapshot2): self
    {
        $this->sourceGroupSnapshot2 = $sourceGroupSnapshot2;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->getCode();
    }
}
