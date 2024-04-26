<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?WorldBuildingCategory $category = null;

    #[ORM\ManyToMany(targetEntity: Synopsis::class, inversedBy: 'articles')]
    private Collection $synopsis;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->synopsis = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?WorldBuildingCategory
    {
        return $this->category;
    }

    public function setCategory(?WorldBuildingCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Synopsis>
     */
    public function getSynopsis(): Collection
    {
        return $this->synopsis;
    }

    public function addSynopsi(Synopsis $synopsi): static
    {
        if (!$this->synopsis->contains($synopsi)) {
            $this->synopsis->add($synopsi);
        }

        return $this;
    }

    public function removeSynopsi(Synopsis $synopsi): static
    {
        $this->synopsis->removeElement($synopsi);

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
}
