<?php

declare(strict_types=1);

namespace App\Entity;

final class Task
{
    private ?int $id;

    private ?string $title;

    private ?string $content;

    private ?int $position;

    private ?int $category;

    /**
     * Get the value of id
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param ?int $id
     *
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return ?string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param ?string $content
     *
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of position
     *
     * @return ?int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * Set the value of position
     *
     * @param ?int $position
     *
     * @return self
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return ?int
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param ?int $category
     *
     * @return self
     */
    public function setCategory(?int $category): self
    {
        $this->category = $category;

        return $this;
    }
}
