<?php

namespace App\Service;

use App\Dto\PropertyCreateDto;
use App\Entity\Property;
use App\Entity\User;
use App\Repository\PropertyRepository;
use App\Repository\TenantRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\CreatePropertyEvent;
use App\Event\UpdatePropertyEvent;
use App\Event\DeletePropertyEvent;

class PropertyService
{
    public function __construct(
        private PropertyRepository $propertyRepository,
        private TenantRepository $tenantRepository,
        private EventDispatcherInterface $eventDispatcher
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

        $changes = [
            'name' => $property->getName(),
            'value' => $property->getValue(),
            'type' => $property->getType(),
            'isConfidential' => $property->isConfidential(),
            'isActive' => $property->isActive(),
            'createdAt' => $property->getCreatedAt(),
        ];

        $this->eventDispatcher->dispatch(new CreatePropertyEvent($property, $changes));

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

        $changes = [
            'name' => $property->getName(),
            'value' => $property->getValue(),
            'type' => $property->getType(),
            'isConfidential' => $property->isConfidential(),
            'isActive' => $property->isActive(),
        ];

        $this->eventDispatcher->dispatch(new UpdatePropertyEvent($property, $changes));

        return $property;
    }

    public function deleteProperty(Property $property): void
    {
        $property->setDeletedAt(new \DateTimeImmutable());
        $this->save($property);

        $this->eventDispatcher->dispatch(new DeletePropertyEvent($property));
    }
}