<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['index'])]
    private ?string $title = null;

    #[ORM\Column(length: 1500, nullable: true)]
    #[Groups(['index'])]
    private ?string $description = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(['index'])]
    private ?string $color = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['index'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['index'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column()]
    #[Groups(['index'])]
    private ?int $position = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?bool $valid = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?bool $archived = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Synopsis $synopsis = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    private ?Chapter $chapter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    public function getSynopsis(): ?Synopsis
    {
        return $this->synopsis;
    }

    public function setSynopsis(?Synopsis $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }
    
    #[Groups(['index'])]
    public function getChapterId(): ?int
    {
        return $this->chapter ? $this->chapter->getId() : null;
    }
}
