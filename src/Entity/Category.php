<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
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

    #[ORM\Column(length: 2500, nullable: true)]
    #[Assert\Length(min: 3, max: 2500)]
    #[Groups(['index'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Synopsis::class, mappedBy: 'categories')]
    private Collection $synopses;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->synopses = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $synopsis->addCategory($this);
        }

        return $this;
    }

    public function removeSynopsis(Synopsis $synopsis): static
    {
        if ($this->synopses->removeElement($synopsis)) {
            $synopsis->removeCategory($this);
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
    #[SerializedName('_links')]
    public function getLinks(): array
    {
        return [
            'delete' => ['href' => '/api/category/'.$this->getId()],
            'update' => ['href' => '/api/category/'.$this->getId()],
        ];
    }
}
