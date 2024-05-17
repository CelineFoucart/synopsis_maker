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
    #[Assert\Length(max: 255)]
    private ?string $link = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $nationality = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $job = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $birthday = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $birthdayPlace = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $deathDate = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $deathPlace = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private $parents = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private ?string $children = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private ?string $siblings = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private ?string $partner = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private ?string $species = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['index'])]
    private ?string $gender = null;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 15000)]
    private ?string $role = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['index'])]
    #[Assert\Length(max: 15000)]
    private ?string $complementary = null;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthdayPlace(): ?string
    {
        return $this->birthdayPlace;
    }

    public function setBirthdayPlace(?string $birthdayPlace): self
    {
        $this->birthdayPlace = $birthdayPlace;

        return $this;
    }

    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }

    public function setDeathDate(?string $deathDate): self
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getDeathPlace(): ?string
    {
        return $this->deathPlace;
    }

    public function setDeathPlace(?string $deathPlace): self
    {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    public function getParents(): ?string
    {
        return $this->parents;
    }

    public function setParents(?string $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    public function getChildren(): ?string
    {
        return $this->children;
    }

    public function setChildren(?string $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getSiblings(): ?string
    {
        return $this->siblings;
    }

    public function setSiblings(?string $siblings): self
    {
        $this->siblings = $siblings;

        return $this;
    }

    public function getPartner(): ?string
    {
        return $this->partner;
    }

    public function setPartner(?string $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

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
    
    public function getComplementary(): ?string
    {
        return $this->complementary;
    }
    
    public function setComplementary(?string $complementary): static
    {
        $this->complementary = $complementary;

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
}
