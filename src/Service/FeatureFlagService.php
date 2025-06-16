<?php

namespace App\Service;

use App\Dto\FeatureFlagCreateDto;
use App\Entity\FeatureFlag;
use App\Entity\User;
use App\Repository\FeatureFlagRepository;
use App\Repository\TenantRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\CreateFeatureFlagEvent;
use App\Event\UpdateFeatureFlagEvent;
use App\Event\DeleteFeatureFlagEvent;

class FeatureFlagService
{
    public function __construct(
        private FeatureFlagRepository $featureFlagRepository,
        private TenantRepository $tenantRepository,
        private EventDispatcherInterface $eventDispatcher
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

        $changes = [
            'name' => $featureFlag->getName(),
            'isActive' => $featureFlag->isActive(),
            'createdAt' => $featureFlag->getCreatedAt(),
        ];

        $this->eventDispatcher->dispatch(new CreateFeatureFlagEvent($featureFlag, $changes));

        return $featureFlag;
    }

    public function updateFeatureFlag(FeatureFlag $featureFlag, array $data): FeatureFlag
    {
        $featureFlag->setName($data['name'] ?? $featureFlag->getName());
        $featureFlag->setIsActive($data['isActive'] ?? $featureFlag->isActive());
        $featureFlag->setUpdatedAt(new \DateTimeImmutable());

        $this->save($featureFlag);

        $changes = [
            'name' => $featureFlag->getName(),
            'isActive' => $featureFlag->isActive(),
            'createdAt' => $featureFlag->getCreatedAt(),
        ];

        $this->eventDispatcher->dispatch(new UpdateFeatureFlagEvent($featureFlag, $changes));

        return $featureFlag;
    }

    public function deleteFeatureFlag(FeatureFlag $featureFlag): void
    {
        $featureFlag->setDeletedAt(new \DateTimeImmutable());
        $this->save($featureFlag);

        $this->eventDispatcher->dispatch(new DeleteFeatureFlagEvent($featureFlag));
    }
}