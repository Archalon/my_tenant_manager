<?php

namespace App\Service;

use App\Entity\Tenant;
use App\Repository\TenantRepository;
use App\Dto\TenantInputDto;

class TenantService
{
    public function __construct(
        private TenantRepository $tenantRepository
    ) {}

    public function save(Tenant $tenant): void
    {
        $this->tenantRepository->save($tenant);
    }

    public function delete(Tenant $tenant): void
    {
        $this->tenantRepository->delete($tenant);
    }

    public function getAllTenants(): array
    {
        return $this->tenantRepository->findAllTenants();
    }

    public function createTenantFromDto(TenantInputDto $dto): Tenant
    {
        $tenant = new Tenant();
        $tenant->setDbName($dto->dbName);
        $tenant->setDbHost($dto->dbHost);
        $tenant->setDbUser($dto->dbUser);
        $tenant->setDbPassword($dto->dbPassword);
        $tenant->setFeatureFlags($dto->featureFlags ?? []);

        $this->save($tenant);

        return $tenant;
    }

    public function updateTenant(Tenant $tenant, array $data): Tenant
    {
        $tenant->setDbName($data['dbName'] ?? $tenant->getDbName());
        $tenant->setDbHost($data['dbHost'] ?? $tenant->getDbHost());
        $tenant->setDbUser($data['dbUser'] ?? $tenant->getDbUser());
        $tenant->setDbPassword($data['dbPassword'] ?? $tenant->getDbPassword());
        $tenant->setFeatureFlags($data['featureFlags'] ?? $tenant->getFeatureFlags());

        $this->save($tenant);

        return $tenant;
    }
}