<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Synopsis;
use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

final class SynopsisHandler
{
    private array $errors = [];

    private Synopsis $synopsis;

    public function __construct(
        private CategoryRepository $categoryRepository, 
        private SluggerInterface $slugger
    ) {
    }

    public function edit(array $data, User $user): static
    {
        $this->errors = [];
        $this->synopsis
            ->setTitle(isset($data['title']) ? $data['title'] : null)
            ->setPitch(isset($data['pitch']) ? $data['pitch'] : null)
            ->setDescription(isset($data['description']) ? $data['description'] : null)
            ->setAuthor($user)
        ;

        if (!isset($data['categories'])) {
            $this->errors = ['categories'=>'Ce champ est obligatoire'];

            return $this;
        }

        $categories = $this->categoryRepository->findByIds($data['categories'], $user);
        if (empty($categories)) {
            $this->errors = ['categories'=>'Ce champ est obligatoire'];

            return $this;
        }

        foreach ($categories as $category) {
            $this->synopsis->addCategory($category);
        }

        if ($this->synopsis->getTitle() === null) {
            $this->errors = ['title'=>'Ce champ est obligatoire'];

            return $this;
        }

        $this->synopsis->setSlug(strtolower((string) $this->slugger->slug($this->synopsis->getTitle())));

        return $this;
    }
    
    public function getSynopsis(): Synopsis
    {
        return $this->synopsis;
    }
    
    public function setSynopsis(Synopsis $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}
