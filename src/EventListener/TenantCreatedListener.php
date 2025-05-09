<?php

namespace App\EventListener;

use App\Event\CreateTenantEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsEventListener(event: CreateTenantEvent::class, method: 'onCreateTenant')]
class TenantCreatedListener
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function createDatabaseForTenant(string $databaseName): void
    {
        $sql = "CREATE DATABASE IF NOT EXISTS {$databaseName}";
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();
    }

    private function createAdminUserInDatabase(string $databaseName, UserInterface $creator): void
    {
        $username = $creator->getUsername();
        $password = $creator->getPassword();

        $sql = "CREATE USER '{$username}'@'localhost' IDENTIFIED BY '{$password}';
                GRANT ALL PRIVILEGES ON {$databaseName}.* TO '{$username}'@'localhost';";
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();
    }

    public function onCreateTenant(CreateTenantEvent $event): void
    {
        $tenant = $event->getTenant();
        $creator = $tenant->getCreatedBy();
        $databaseName = 'my_tenant_manager_' . $tenant->getCode();

        $this->createDatabaseForTenant($databaseName);
        $this->createAdminUserInDatabase($databaseName, $creator);
    }
}