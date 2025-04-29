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

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read', 'audit:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'audit:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['user:read', 'audit:read'])]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['user:read', 'audit:read'])]
    private ?bool $isActive = null;

    #[ORM\Column]
    #[Groups(['user:read', 'audit:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read', 'audit:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['audit:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, Tenant>
     */
    #[ORM\OneToMany(targetEntity: Tenant::class, mappedBy: 'createdBy')]
    private Collection $tenants;

    /**
     * @var Collection<int, Property>
     */
    #[ORM\OneToMany(targetEntity: Property::class, mappedBy: 'createdBy')]
    private Collection $properties;

    /**
     * @var Collection<int, FeatureFlag>
     */
    #[ORM\OneToMany(targetEntity: FeatureFlag::class, mappedBy: 'createdBy')]
    private Collection $featureFlags;

    /**
     * @var Collection<int, Audit>
     */
    #[ORM\OneToMany(targetEntity: Audit::class, mappedBy: 'user')]
    private Collection $audits;

    public function __construct()
    {
        $this->tenants = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->featureFlags = new ArrayCollection();
        $this->audits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->password = "";
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Collection<int, Tenant>
     */
    public function getTenants(): Collection
    {
        return $this->tenants;
    }

    public function addTenant(Tenant $tenant): static
    {
        if (!$this->tenants->contains($tenant)) {
            $this->tenants->add($tenant);
            $tenant->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTenant(Tenant $tenant): static
    {
        if ($this->tenants->removeElement($tenant)) {
            // set the owning side to null (unless already changed)
            if ($tenant->getCreatedBy() === $this) {
                $tenant->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getCreatedBy() === $this) {
                $property->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FeatureFlag>
     */
    public function getFeatureFlags(): Collection
    {
        return $this->featureFlags;
    }

    public function addFeatureFlag(FeatureFlag $featureFlag): static
    {
        if (!$this->featureFlags->contains($featureFlag)) {
            $this->featureFlags->add($featureFlag);
            $featureFlag->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFeatureFlag(FeatureFlag $featureFlag): static
    {
        if ($this->featureFlags->removeElement($featureFlag)) {
            // set the owning side to null (unless already changed)
            if ($featureFlag->getCreatedBy() === $this) {
                $featureFlag->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Audit>
     */
    public function getAudits(): Collection
    {
        return $this->audits;
    }

    public function addAudit(Audit $audit): static
    {
        if (!$this->audits->contains($audit)) {
            $this->audits->add($audit);
            $audit->setUser($this);
        }

        return $this;
    }

    public function removeAudit(Audit $audit): static
    {
        if ($this->audits->removeElement($audit)) {
            // set the owning side to null (unless already changed)
            if ($audit->getUser() === $this) {
                $audit->setUser(null);
            }
        }

        return $this;
    }
}