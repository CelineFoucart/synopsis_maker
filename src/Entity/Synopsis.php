<?php

namespace App\Entity;

use App\Repository\SynopsisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SynopsisRepository::class)]
class Synopsis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index', 'public', 'show-author'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['index', 'public', 'show-author'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['index', 'public', 'show-author'])]
    private ?string $slug = null;

    #[ORM\Column(length: 3000, nullable: true)]
    #[Assert\Length(min: 2, max: 2500)]
    #[Groups(['index', 'public', 'show-author'])]
    private ?string $pitch = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 2, max: 25000)]
    #[Groups(['index'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['index', 'public', 'show-author'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['index', 'show-author'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'synopses')]
    #[Groups(['index', 'public', 'show-author'])]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'synopses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['index', 'public'])]
    private ?User $author = null;

    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'synopsis', orphanRemoval: true)]
    #[Groups(['index'])]
    private Collection $episodes;

    #[ORM\OneToMany(targetEntity: Chapter::class, mappedBy: 'synopsis', orphanRemoval: true)]
    #[Groups(['index'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
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

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?array $tasks = null;

    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'synopses')]
    #[OrderBy(['title' => 'ASC'])]
    #[Groups(['index'])]
    private Collection $places;

    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'synopses')]
    #[OrderBy(['name' => 'ASC'])]
    #[Groups(['index'])]
    private Collection $characters;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'synopses')]
    #[OrderBy(['title' => 'ASC'])]
    #[Groups(['index'])]
    private Collection $articles;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    private ?string $worldbuildingHome = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?array $settings = [];

    #[ORM\Column(nullable: true)]
    private ?bool $public = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->episodes = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->places = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->setSettings();
        $this->public = false;
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

    /**
     * Get the value of tasks.
     */
    public function getTasks(): ?array
    {
        return null !== $this->tasks ? $this->tasks : [];
    }

    /**
     * Set the value of tasks.
     */
    public function setTasks(?array $tasks): static
    {
        $this->tasks = null !== $tasks ? $tasks : [];

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): static
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
        }

        return $this;
    }

    public function removePlace(Place $place): static
    {
        $this->places->removeElement($place);

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        $this->characters->removeElement($character);

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addSynopsis($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeSynopsis($this);
        }

        return $this;
    }

    public function getWorldbuildingHome(): ?string
    {
        return $this->worldbuildingHome;
    }

    public function setWorldbuildingHome(?string $worldbuildingHome): static
    {
        $this->worldbuildingHome = $worldbuildingHome;

        return $this;
    }

    public function getSettings(): ?array
    {
        if (null === $this->settings) {
            $this->setSettings();
        }

        $this->settings['isPublic'] = $this->isPublic();

        return $this->settings;
    }

    public function setSettings(?array $settings = []): self
    {
        if (null === $settings || empty($settings)) {
            $settings = [
                'appendPitch' => true,
                'appendDescription' => true,
                'appendCategories' => true,
                'appendChapters' => true,
                'appendChapterTitles' => true,
                'appendEpisodeTitles' => true,
                'appendEpisodeCharacters' => true,
                'appendEpisodePlaces' => true,
                'appendNotes' => true,
                'appendEpisodes' => true,
                'appendCharacters' => true,
                'appendPlaces' => true,
                'appendWorldBuildingHome' => true,
                'appendArticles' => true,
                'isPublic' => false,
                'showContent' => false,
                'showCharacters' => false,
                'showPlaces' => false,
            ];
        }

        $this->settings = $settings;
        $this->setPublic(isset($this->settings['isPublic']) ? (bool) $this->settings['isPublic'] : false);

        return $this;
    }

    public function isPublic(): bool
    {
        return  (bool) $this->public;
    }

    public function setPublic(?bool $public): static
    {
        $this->public = $public;

        return $this;
    }
}
