<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['index'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 15000, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 15000)]
    private ?string $role = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 15000)]
    private ?string $complementary = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 255)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: Synopsis::class, mappedBy: 'places')]
    private Collection $synopses;

    #[ORM\ManyToMany(targetEntity: Episode::class, mappedBy: 'places')]
    private Collection $episodes;

    public function __construct()
    {
        $this->synopses = new ArrayCollection();
        $this->episodes = new ArrayCollection();
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

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
    
    public function getComplementary(): ?string
    {
        return $this->complementary;
    }
    
    public function setComplementary(?string $complementary): static
    {
        $this->complementary = $complementary;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

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
     * @return Collection<int, Synopsis>
     */
    public function getSynopses(): Collection
    {
        return $this->synopses;
    }

    public function addSynopsis(Synopsis $synopsis): static
    {
        if (!$this->synopses->contains($synopsis)) {
            $this->synopses->add($synopsis);
            $synopsis->addPlace($this);
        }

        return $this;
    }

    public function removeSynopsis(Synopsis $synopsis): static
    {
        if ($this->synopses->removeElement($synopsis)) {
            $synopsis->removePlace($this);
        }

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
            $episode->addPlace($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            $episode->removePlace($this);
        }

        return $this;
    }

    #[Groups(['index'])]
    public function getRelations(): array
    {
        $relations = [];
        foreach ($this->getSynopses() as $synopsis) {
            $relations[] = ['id' => $synopsis->getId(), 'slug' => $synopsis->getSlug(), 'title' => $synopsis->getTitle()];
        }

        return $relations;
    }

    #[Groups(['index'])]
    #[SerializedName('_links')]
    public function getLinks(): array
    {
        return [
            'delete' => ['href' => '/api/place/'.$this->getId()],
            'update' => ['href' => '/api/place/'.$this->getId()],
        ];
    }
}
