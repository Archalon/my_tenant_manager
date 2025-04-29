<?php

namespace App\Entity;

use App\Repository\FeatureFlagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FeatureFlagRepository::class)]
class FeatureFlag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'featureFlags')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'featureFlags')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?User $createdBy = null;

    #[ORM\Column(length: 255)]
    #[Groups(['feature_flag:read', 'audit:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?bool $isActive = null;

    #[ORM\Column]
    #[Groups(['feature_flag:read', 'audit:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['audit:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['audit:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): static
    {
        $this->tenant = $tenant;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
}
