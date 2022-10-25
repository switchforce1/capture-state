<?php

namespace App\Entity;

use App\Repository\SourceGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceGroupRepository::class)]
class SourceGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'sourceGroup', targetEntity: Source::class)]
    private $sources;

    #[ORM\OneToMany(mappedBy: 'sourceGroup', targetEntity: SourceGroupSnapshot::class, orphanRemoval: true)]
    private $sourceGroupSnapshots;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->sourceGroupSnapshots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
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

    /**
     * @return Collection<int, Source>
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setSourceGroup($this);
        }

        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->removeElement($source)) {
            // set the owning side to null (unless already changed)
            if ($source->getSourceGroup() === $this) {
                $source->setSourceGroup(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, SourceGroupSnapshot>
     */
    public function getSourceGroupSnapshots(): Collection
    {
        return $this->sourceGroupSnapshots;
    }

    public function addSourceGroupSnapshot(SourceGroupSnapshot $sourceGroupSnapshot): self
    {
        if (!$this->sourceGroupSnapshots->contains($sourceGroupSnapshot)) {
            $this->sourceGroupSnapshots[] = $sourceGroupSnapshot;
            $sourceGroupSnapshot->setSourceGroup($this);
        }

        return $this;
    }

    public function removeSourceGroupSnapshot(SourceGroupSnapshot $sourceGroupSnapshot): self
    {
        if ($this->sourceGroupSnapshots->removeElement($sourceGroupSnapshot)) {
            // set the owning side to null (unless already changed)
            if ($sourceGroupSnapshot->getSourceGroup() === $this) {
                $sourceGroupSnapshot->setSourceGroup(null);
            }
        }

        return $this;
    }
}
