<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
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
    private ?string $name = null;

    #[ORM\Column(length: 2500, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 2500)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 25000)]
    private ?string $appearance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 25000)]
    private ?string $biography = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['index'])]
    private ?array $personality = null;

    #[ORM\ManyToMany(targetEntity: Synopsis::class, mappedBy: 'characters')]
    private Collection $synopses;

    #[ORM\ManyToMany(targetEntity: Episode::class, mappedBy: 'characters')]
    private Collection $episodes;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['index'])]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->synopses = new ArrayCollection();
        $this->episodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getAppearance(): ?string
    {
        return $this->appearance;
    }

    public function setAppearance(?string $appearance): static
    {
        $this->appearance = $appearance;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getPersonality(): ?array
    {
        return $this->personality;
    }

    public function setPersonality(?array $personality): static
    {
        $this->personality = $personality;

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
            $synopsis->addCharacter($this);
        }

        return $this;
    }

    public function removeSynopsis(Synopsis $synopsis): static
    {
        if ($this->synopses->removeElement($synopsis)) {
            $synopsis->removeCharacter($this);
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
            $episode->addCharacter($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            $episode->removeCharacter($this);
        }

        return $this;
    }

    #[Groups(['index'])]
    public function getParents(): array
    {
        $parents = [];
        foreach ($this->getSynopses() as $synopsis) {
            $parents[] = ['id' => $synopsis->getId(), 'slug' => $synopsis->getSlug(), 'title' => $synopsis->getTitle()];
        }

        return $parents;
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

    #[Groups(['index'])]
    #[SerializedName('_links')]
    public function getLinks(): array
    {
        return [
            'delete' => ['href' => '/api/character/'.$this->getId()],
            'update' => ['href' => '/api/character/'.$this->getId()],
        ];
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
}
