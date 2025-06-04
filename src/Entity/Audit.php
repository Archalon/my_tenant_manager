<?php

namespace App\Entity;

use App\Repository\AuditRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuditRepository::class)]
class Audit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'audits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['audit:read'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups(['audit:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['audit:read'])]
    private ?string $resourceType = null;

    #[ORM\Column(length: 255)]
    #[Groups(['audit:read'])]
    private ?string $resourceId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['audit:read'])]
    private ?string $action = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['audit:read'])]
    private ?array $context = [];

    #[ORM\Column]
    #[Groups(['audit:read'])]
    private ?DateTimeImmutable $registeredAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getResourceType(): ?string
    {
        return $this->resourceType;
    }

    public function setResourceType(string $resourceType): static
    {
        $this->resourceType = $resourceType;
        return $this;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(string $resourceId): static
    {
        $this->resourceId = $resourceId;
        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function getRegisteredAt(): ?DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(DateTimeImmutable $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }
}
