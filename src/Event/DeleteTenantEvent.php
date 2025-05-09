<?php

namespace App\Event;

use App\Entity\Tenant;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteTenantEvent extends Event
{
    public const NAME = 'tenant.delete';

    private Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): Tenant
    {
        return $this->tenant;
    }
}