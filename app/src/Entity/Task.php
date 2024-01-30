<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $TaskId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateOfExpiry = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    public function getId(): ?int
    {
        return $this->TaskId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $titel): static
    {
        $this->title = $titel;

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

    public function getDateOfExpiry(): ?\DateTimeImmutable
    {
        return $this->dateOfExpiry;
    }

    public function setDateOfExpiry(\DateTimeImmutable $dateOfExpiry): static
    {
        $this->dateOfExpiry = $dateOfExpiry;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return[
            'id' => $this->TaskId,
            'title' => $this->title,
            'description' => $this->description,
            'dateOfexpiry' => $this->dateOfExpiry,
            'priority' => $this->priority,
            'deleted' => $this->deleted
        ];

    }
}
