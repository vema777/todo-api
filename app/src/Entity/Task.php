<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
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
    private ?\DateTime $dateOfExpiry = null;

    #[ORM\Column]
    private ?\DateTime $createdAt;

    #[ORM\Column]
    private ?\DateTime $updatedAt;

    #[ORM\Column]
    private ?int $priority = 3;

    #[ORM\Column]
    private ?bool $deleted = false;

    #[ORM\Column]
    private ?bool $done = false;

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
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return $this
     */
    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

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
            'deleted' => $this->deleted,
            'done' => $this->done
        ];
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(?bool $done): void
    {
        $this->done = $done;
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
