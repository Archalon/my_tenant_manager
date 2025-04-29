<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'audit:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?User $createdBy = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'audit:read'])]
    private ?string $value = null;

    #[ORM\Column(length: 50)]
    #[Groups(['property:read', 'audit:read'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?bool $isConfidential = null;

    #[ORM\Column]
    #[Groups(['property:read', 'audit:read'])]
    private ?bool $isActive = null;

    #[ORM\Column]
    #[Groups(['property:read', 'audit:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'audit:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['audit:read'])]
    private ?\DateTimeImmutable $deletedAt = null;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isConfidential(): ?bool
    {
        return $this->isConfidential;
    }

    public function setIsConfidential(bool $isConfidential): static
    {
        $this->isConfidential = $isConfidential;

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