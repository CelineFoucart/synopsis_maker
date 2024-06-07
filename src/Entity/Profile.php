<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 2, max: 15000)]
    private ?string $about = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $rank = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $interests = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(?string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->interests;
    }

    public function setInterests(?string $interests): static
    {
        $this->interests = $interests;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if (null === $user && null !== $this->user) {
            $this->user->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $user && $user->getProfile() !== $this) {
            $user->setProfile($this);
        }

        $this->user = $user;

        return $this;
    }
}
