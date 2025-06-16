<?php

namespace App\EventListener;

use App\Entity\Tenant;
use App\Entity\User;
use App\Event\CreateTenantEvent;
use App\Service\PropertyService;
use App\Dto\PropertyCreateDto;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsEventListener(event: CreateTenantEvent::class, method: 'onCreateTenant')]
class TenantCreatedListener
{
    private Connection $connection;
    private PropertyService $propertyService;

    public function __construct(ManagerRegistry $doctrine, PropertyService $propertyService)
    {
        $this->connection = $doctrine->getConnection();
        $this->propertyService = $propertyService;
    }

    private function createDatabaseForTenant(string $databaseName): void
    {
        $sql = "CREATE DATABASE IF NOT EXISTS {$databaseName}";
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();
    }

    private function createAdminUserInDatabase(string $databaseName, string $adminUsername, string $adminPassword): void
    {
        $sql = "CREATE USER '{$adminUsername}'@'localhost' IDENTIFIED BY '{$adminPassword}';
                GRANT ALL PRIVILEGES ON {$databaseName}.* TO '{$adminUsername}'@'localhost';";
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();
    }

    private function createAdminProperties(Tenant $tenant, UserInterface $creator): void
    {
        $adminUsername = $tenant->getCode();
        $adminPassword = bin2hex(random_bytes(8));
        $databaseName = 'my_tenant_manager_' . $tenant->getCode(); 

        // Criar Property para o nome da base de dados
        $this->propertyService->createPropertyFromDto(new PropertyCreateDto(
            name: 'database_name',
            value: $databaseName,
            type: 'string', 
            isActive: true, 
            isConfidential: false,
            tenantCode: $tenant->getCode()
        ), $creator);

        // Criar Property para o nome de usuário admin
        $this->propertyService->createPropertyFromDto(new PropertyCreateDto(
            name: 'database_username',
            value: $adminUsername,
            type: 'string',
            isActive: true,
            isConfidential: false,
            tenantCode: $tenant->getCode()
        ), $creator);

        // Criar Property para a senha do usuário admin
        $this->propertyService->createPropertyFromDto(new PropertyCreateDto(
            name: 'database_password',
            value: $adminPassword,
            type: 'string',
            isActive: true,
            isConfidential: true,
            tenantCode: $tenant->getCode()
        ), $creator);

        // Chamar o método para criar o usuário admin na base de dados do Tenant
        $this->createAdminUserInDatabase("my_tenant_manager_" . $tenant->getCode(), $adminUsername, $adminPassword);
    }

    public function onCreateTenant(CreateTenantEvent $event): void
    {
        $tenant = $event->getTenant();
        $creator = $tenant->getCreatedBy();
        $databaseName = 'my_tenant_manager_' . $tenant->getCode();

        $this->createDatabaseForTenant($databaseName);
        $this->createAdminProperties($tenant, $creator);
    }
}