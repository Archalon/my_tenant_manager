<?php

namespace App\Entity;

use App\Repository\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
class Tenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read', 'audit:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read', 'audit::read'])]
    private ?string $code = null;

    #[ORM\Column(length: 50)]
    #[Groups(['tenant:read', 'audit::read'])]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(['tenant:read', 'audit:read'])]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'tenants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?User $createdBy = null;

    #[ORM\Column]
    #[Groups(['tenant:read', 'audit:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tenant:read', 'audit:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['audit:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, Property>
     */
    #[ORM\OneToMany(targetEntity: Property::class, mappedBy: 'tenant')]
    private Collection $properties;

    /**
     * @var Collection<int, FeatureFlag>
     */
    #[ORM\OneToMany(targetEntity: FeatureFlag::class, mappedBy: 'tenant')]
    private Collection $featureFlags;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->featureFlags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

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
            $property->setTenant($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getTenant() === $this) {
                $property->setTenant(null);
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
            $featureFlag->setTenant($this);
        }

        return $this;
    }

    public function removeFeatureFlag(FeatureFlag $featureFlag): static
    {
        if ($this->featureFlags->removeElement($featureFlag)) {
            // set the owning side to null (unless already changed)
            if ($featureFlag->getTenant() === $this) {
                $featureFlag->setTenant(null);
            }
        }

        return $this;
    }
}