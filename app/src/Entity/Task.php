<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $TaskId = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $dateOfExpiry = null;

    #[ORM\Column]
    private ?\DateTime $createdAt;

    #[ORM\Column]
    private ?\DateTime $updatedAt;

    #[ORM\Column]
    private int $priority = 3;

    #[ORM\Column(nullable: false)]
    private ?bool $isDeleted = false;

    #[ORM\Column]
    private ?bool $isDone = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->TaskId;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $titel
     * @return $this
     */
    public function setTitle(string $titel): static
    {
        $this->title = $titel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDateOfExpiry(): ?\DateTime
    {
        return $this->dateOfExpiry;
    }

    /**
     * @param \DateTimeImmutable $dateOfExpiry
     * @return $this
     */
    public function setDateOfExpiry(\DateTime $dateOfExpiry): static
    {
        $this->dateOfExpiry = $dateOfExpiry;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     * @return $this
     */
    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->TaskId,
            'title' => $this->title,
            'description' => $this->description,
            'dateOfexpiry' => $this->dateOfExpiry,
            'priority' => $this->priority,
            'isDeleted' => $this->isDeleted,
            'isDone' => $this->isDone,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(?bool $isDone): void
    {
        $this->isDone = $isDone;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
