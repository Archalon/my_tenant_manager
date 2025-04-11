<?php

namespace App\Entity;

use App\Repository\TenantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
class Tenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tenant:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read'])]
    private ?string $dbName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read'])]
    private ?string $dbHost = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read'])]
    private ?string $dbUser = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tenant:read'])]
    private ?string $dbPassword = null;

    #[ORM\Column]
    #[Groups(['tenant:read'])]
    private array $featureFlags = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): static
    {
        $this->dbName = $dbName;

        return $this;
    }

    public function getDbHost(): ?string
    {
        return $this->dbHost;
    }

    public function setDbHost(string $dbHost): static
    {
        $this->dbHost = $dbHost;

        return $this;
    }

    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    public function setDbUser(string $dbUser): static
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    public function setDbPassword(string $dbPassword): static
    {
        $this->dbPassword = $dbPassword;

        return $this;
    }

    public function getFeatureFlags(): array
    {
        return $this->featureFlags;
    }

    public function setFeatureFlags(array $featureFlags): static
    {
        $this->featureFlags = $featureFlags;

        return $this;
    }
}
