<?php

namespace App\Service;

use App\Dto\FeatureFlagCreateDto;
use App\Entity\FeatureFlag;
use App\Entity\User;
use App\Repository\FeatureFlagRepository;
use App\Repository\TenantRepository;

class FeatureFlagService
{
    public function __construct(
        private FeatureFlagRepository $featureFlagRepository,
        private TenantRepository $tenantRepository
    ) {}

    public function save(FeatureFlag $featureFlag): void
    {
        $this->featureFlagRepository->save($featureFlag);
    }

    public function delete(FeatureFlag $featureFlag): void
    {
        $this->featureFlagRepository->delete($featureFlag);
    }

    public function getAllFeatureFlags(): array
    {
        return $this->featureFlagRepository->findAll();
    }

    public function getByName(string $name): ?FeatureFlag
    {
        return $this->featureFlagRepository->findByName($name);
    }

    public function createFeatureFlagFromDto(FeatureFlagCreateDto $dto, User $creator): FeatureFlag
    {
        // Buscar o tenant pelo código fornecido no DTO
        $tenant = $this->tenantRepository->findOneBy(['code' => $dto->tenantCode]);

        if (!$tenant) {
            throw new \Exception('Tenant não encontrado.');
        }

        $featureFlag = new FeatureFlag();
        $featureFlag->setName($dto->name);
        $featureFlag->setIsActive($dto->isActive);
        $featureFlag->setTenant($tenant);
        $featureFlag->setCreatedBy($creator);
        $featureFlag->setCreatedAt(new \DateTimeImmutable());

        $this->save($featureFlag);

        return $featureFlag;
    }

    public function updateFeatureFlag(FeatureFlag $featureFlag, array $data): FeatureFlag
    {
        // Atualiza os campos da feature flag
        $featureFlag->setName($data['name'] ?? $featureFlag->getName());
        $featureFlag->setIsActive($data['isActive'] ?? $featureFlag->isActive());
        $featureFlag->setUpdatedAt(new \DateTimeImmutable());

        // Salva as mudanças
        $this->save($featureFlag);

        return $featureFlag;
    }
}