<?php

namespace App\Service;

use App\Dto\PropertyCreateDto;
use App\Entity\Property;
use App\Entity\User;
use App\Repository\PropertyRepository;
use App\Repository\TenantRepository;

class PropertyService
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private TenantRepository $tenantRepository
    ) {}

    public function save(Property $property): void
    {
        $this->propertyRepository->save($property);
    }

    public function delete(Property $property): void
    {
        $this->propertyRepository->delete($property);
    }

    public function getAllProperties(): array
    {
        return $this->propertyRepository->findAllProperties();
    }

    public function getByName(string $name): ?Property
    {
        return $this->propertyRepository->findByName($name);
    }

    public function createPropertyFromDto(PropertyCreateDto $dto, User $creator): Property
    {
        $tenant = $this->tenantRepository->findByCode($dto->tenantCode);

        if (!$tenant) {
            throw new \Exception('Tenant not found.');
        }

        $property = new Property();
        $property->setName($dto->name);
        $property->setValue($dto->value);
        $property->setType($dto->type);
        $property->setIsConfidential($dto->isConfidential);
        $property->setIsActive($dto->isActive);
        $property->setTenant($tenant);
        $property->setCreatedBy($creator);
        $property->setCreatedAt(new \DateTimeImmutable());

        $this->save($property);

        return $property;
    }

    public function updateProperty(Property $property, array $data): Property
    {
        $property->setName($data['name'] ?? $property->getName());
        $property->setValue($data['value'] ?? $property->getValue());
        $property->setType($data['type'] ?? $property->getType());
        $property->setIsConfidential($data['isConfidential'] ?? $property->isConfidential());
        $property->setIsActive($data['isActive'] ?? $property->isActive());
        $property->setUpdatedAt(new \DateTimeImmutable());

        $this->save($property);

        return $property;
    }
}