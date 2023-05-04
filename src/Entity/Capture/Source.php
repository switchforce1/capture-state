<?php

declare(strict_types=1);

namespace App\Entity\Capture;

use App\Repository\Capture\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
class Source
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'GET';
    private const METHOD_PUT = 'GET';
    private const TYPE_JSON_URL = 'JSON_URL';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\Column(type: 'string', length: 255)]
    private $typeCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $url;

    #[ORM\OneToMany(mappedBy: 'source', targetEntity: Comparison::class, orphanRemoval: true)]
    private $comparisons;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'sources')]
    private $tags;

    #[ORM\ManyToOne(targetEntity: SourceGroup::class, inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: false)]
    private $sourceGroup;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $method = self::METHOD_GET;

    public function __construct()
    {
        $this->comparisons = new ArrayCollection();
        $this->typeCode = self::TYPE_JSON_URL;
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getTypeCode(): ?string
    {
        return $this->typeCode;
    }

    public function setTypeCode(string $typeCode): self
    {
        $this->typeCode = $typeCode;

        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Collection<int, Comparison>
     */
    public function getComparisons(): Collection
    {
        return $this->comparisons;
    }

    public function addComparison(Comparison $comparison): self
    {
        if (!$this->comparisons->contains($comparison)) {
            $this->comparisons[] = $comparison;
            $comparison->setSource($this);
        }

        return $this;
    }

    public function removeComparison(Comparison $comparison): self
    {
        if ($this->comparisons->removeElement($comparison)) {
            // set the owning side to null (unless already changed)
            if ($comparison->getSource() === $this) {
                $comparison->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

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

    public function __toString(): string
    {
        return $this->getLabel();
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
