<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['main'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['main'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['main'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    private $plainPassword;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['main'])]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['main'])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['main'])]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column]
    private bool $isDeleted = false;

    /**
     * @var Collection|ArrayCollection vom Nutzer erstellte Aufgaben
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: ApiToken::class)]
    private Collection $apiTokens;

    /* Scopes given during API authentication */
    private ?array $accessTokenScopes = null;

    /**
     * @var Collection|ArrayCollection Organisationen, bei denen der Nutzer Mitglied ist
     */
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'users')]
    private Collection $organization;

    /**
     * @var Collection|ArrayCollection Organisationen, bei denen der Nutzer der Eigentümer der Organisation ist
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Organization::class)]
    private Collection $organizationsOwned;

    /**
     * @var Collection|ArrayCollection diesem Nutzer zugewiesene Aufgaben
     */
    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'assignees')]
    private Collection $assignedTasks;


    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TodoList::class)]
    private Collection $lists;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->lists = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->apiTokens = new ArrayCollection();
        $this->organization = new ArrayCollection();
        $this->organizationsOwned = new ArrayCollection();
        $this->assignedTasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        if (null === $this->accessTokenScopes) {
            // logged in as a full, normal user
            $roles = $this->roles;
            $roles[] = 'ROLE_FULL_USER';
        } else {
            $roles = $this->accessTokenScopes;
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
       $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): static
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
            $apiToken->setOwnedBy($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): static
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getOwnedBy() === $this) {
                $apiToken->setOwnedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getValidTokenStrings(): array
    {
        return $this->getApiTokens()
            ->filter(fn (ApiToken $token) => $token->isValid())
            ->map(fn (ApiToken $token) => $token->getToken())
            ->toArray()
        ;
    }

    public function markAsTokenAuthenticated(array $scopes): void
    {
        $this->accessTokenScopes = $scopes;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganization(): Collection
    {
        return $this->organization;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organization->contains($organization)) {
            $this->organization->add($organization);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        $this->organization->removeElement($organization);

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizationsOwned(): Collection
    {
        return $this->organizationsOwned;
    }

    public function addOrganizationsOwned(Organization $organizationsOwned): static
    {
        if (!$this->organizationsOwned->contains($organizationsOwned)) {
            $this->organizationsOwned->add($organizationsOwned);
            $organizationsOwned->setOwner($this);
        }

        return $this;
    }

    public function removeOrganizationsOwned(Organization $organizationsOwned): static
    {
        if ($this->organizationsOwned->removeElement($organizationsOwned)) {
            // set the owning side to null (unless already changed)
            if ($organizationsOwned->getOwner() === $this) {
                $organizationsOwned->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAssignedTasks(): Collection
    {
        return $this->assignedTasks;
    }

    public function addAssignedTask(Task $assignedTask): static
    {
        if (!$this->assignedTasks->contains($assignedTask)) {
            $this->assignedTasks->add($assignedTask);
            $assignedTask->addAssignee($this);
        }

        return $this;
    }

    public function removeAssignedTask(Task $assignedTask): static
    {
        if ($this->assignedTasks->removeElement($assignedTask)) {
            $assignedTask->removeAssignee($this);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }

    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function setLists(Collection $lists): mixed
    {
        $this->lists = $lists;

        return $this;
    }
}
