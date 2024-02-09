<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateOfExpiry = null;

    #[ORM\Column]
    private DateTime $createdAt;

    #[ORM\Column]
    private DateTime $updatedAt;

    #[ORM\Column]
    private int $priority = 3;

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column]
    private bool $isDone = false;

    #[ORM\ManyToOne(targetEntity: TodoList::class,cascade:["persist"],  inversedBy: 'tasks')]
    private TodoList $list;

    /**
     * @var User|null creator / author of the task
     */
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    /**
     * @var Organization|null die Organisation, zu der diese Aufgabe gehört
     */
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Organization $organization = null;

    /**
     * @var bool|null ob die Aufgabe zu einer Organisation gehört oder nicht
     */
    #[ORM\Column]
    private ?bool $isOrganizational = null;

    /**
     * @var Collection|ArrayCollection Nutzer, denen die Aufgabe zu erledigen zugewiesen wurde
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'assignedTasks')]
    private Collection $assignees;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->assignees = new ArrayCollection();
        $this->isOrganizational = false; //Standardwert für erstellte Aufgaben ist false
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function getIsDeleted(): ?bool
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'dateOfExpiry' => $this->dateOfExpiry,
            'priority' => $this->priority,
            'isDone' => $this->isDone,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'list' => $this->list,
            'isOrganizational' => $this->isOrganizational,
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

    public function getList(): TodoList
    {
        return $this->list;
    }

    public function setList(TodoList $list): static
    {
        $this->list = $list;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getIsOrganizational(): ?bool
    {
        return $this->isOrganizational;
    }

    public function setIsOrganizational(bool $isOrganisational): static
    {
        $this->isOrganizational = $isOrganisational;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignees(): Collection
    {
        return $this->assignees;
    }

    public function addAssignee(User $assignee): static
    {
        if (!$this->assignees->contains($assignee)) {
            $this->assignees->add($assignee);
        }

        return $this;
    }

    public function removeAssignee(User $assignee): static
    {
        $this->assignees->removeElement($assignee);

        return $this;
    }
}
