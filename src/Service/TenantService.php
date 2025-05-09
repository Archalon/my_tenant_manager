<?php

namespace App\Service;

use App\Dto\TenantCreateDto;
use App\Entity\Tenant;
use App\Repository\TenantRepository;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\CreateTenantEvent;
use App\Event\UpdateTenantEvent;
use App\Event\DeleteTenantEvent;


class TenantService
{
    public function __construct(
        private TenantRepository $tenantRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function save(Tenant $tenant): void
    {
        $this->tenantRepository->save($tenant);
    }

    public function deleteTenant(Tenant $tenant): void
    {
        $tenant->setDeletedAt(new \DateTimeImmutable());
        $this->save($tenant);

        $this->eventDispatcher->dispatch(new DeleteTenantEvent($tenant));
    }

    public function getAllTenants(): array
    {
        return $this->tenantRepository->findAllTenants();
    }

    public function getByCode(string $code): ?Tenant
    {
        return $this->tenantRepository->findByCode($code);
    }

    public function createTenantFromDto(TenantCreateDto $dto, User $creator): Tenant
    {
        if ($this->tenantRepository->findByCode($dto->code)) {
            throw new \Exception('Tenant code já registado.');
        }

        $tenant = new Tenant();
        $tenant->setName($dto->name);
        $tenant->setCode($dto->code);
        $tenant->setStatus('created');
        $tenant->setIsActive(true);
        $tenant->setCreatedBy($creator);
        $tenant->setCreatedAt(new \DateTimeImmutable());

        $this->save($tenant);

        $changes = [
            'name' => $tenant->getName(),
            'code' => $tenant->getCode(),
            'status' => $tenant->getStatus(),
            'isActive' => $tenant->isActive(),
            'createdAt' => $tenant->getCreatedAt(),
        ];

        $this->eventDispatcher->dispatch(new CreateTenantEvent($tenant, $changes));

        return $tenant;
    }

    public function updateTenant(Tenant $tenant, array $data): Tenant
    {
        $tenant->setName($data['name'] ?? $tenant->getName());
        $tenant->setStatus($data['status'] ?? $tenant->getStatus());
        $tenant->setIsActive($data['is_active'] ?? $tenant->isActive());
        $tenant->setUpdatedAt(new \DateTimeImmutable());

        $this->save($tenant);

        $this->eventDispatcher->dispatch(new UpdateTenantEvent($tenant, $data));

        return $tenant;
    }
}