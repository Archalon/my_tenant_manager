<?php

namespace App\Event;

use App\Entity\Tenant;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateTenantEvent extends Event
{
    public const NAME = 'tenant.update';

    private Tenant $tenant;
    private array $changes;

    public function __construct(Tenant $tenant, array $changes)
    {
        $this->tenant = $tenant;
        $this->changes = $changes;
    }

    public function getTenant(): Tenant
    {
        return $this->tenant;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }
}