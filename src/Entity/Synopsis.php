<?php

namespace App\Entity;

use App\Repository\SynopsisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: SynopsisRepository::class)]
class Synopsis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['index'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['index'])]
    private ?string $slug = null;

    #[ORM\Column(length: 3000, nullable: true)]
    #[Assert\Length(min: 2, max: 2500)]
    #[Groups(['index'])]
    private ?string $pitch = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 2, max: 25000)]
    #[Groups(['index'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['index'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['index'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'synopses')]
    #[Groups(['index'])]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'synopses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'synopsis', orphanRemoval: true)]
    #[Groups(['index'])]
    private Collection $episodes;

    #[ORM\OneToMany(targetEntity: Chapter::class, mappedBy: 'synopsis', orphanRemoval: true)]
    #[Groups(['index'])]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $chapters;

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?bool $archived = null;

    #[ORM\Column(length: 1500, nullable: true)]
    #[Groups(['index'])]
    private ?string $legend = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    private ?string $notes = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->episodes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPitch(): ?string
    {
        return $this->pitch;
    }

    public function setPitch(?string $pitch): static
    {
        $this->pitch = $pitch;

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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): static
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setSynopsis($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getSynopsis() === $this) {
                $episode->setSynopsis(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): static
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setSynopsis($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): static
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getSynopsis() === $this) {
                $chapter->setSynopsis(null);
            }
        }

        return $this;
    }

    #[Groups(['index'])]
    #[SerializedName('_links')]
    public function getLinks(): array
    {
        return [
            'self' => ['href' => '/api/synopsis/'.$this->getId()],
            'delete' => ['href' => '/api/synopsis/'.$this->getId()],
            'update' => ['href' => '/api/synopsis/'.$this->getId()],
        ];
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

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(?string $legend): static
    {
        $this->legend = $legend;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
