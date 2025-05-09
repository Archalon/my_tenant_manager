<?php

namespace App\EventListener;

use App\Entity\Audit;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Repository\AuditRepository;
use App\Event\CreateUserEvent;
use App\Event\UpdateUserEvent;
use App\Event\DeleteUserEvent;
use App\Event\CreateTenantEvent;
use App\Event\UpdateTenantEvent;
use App\Event\DeleteTenantEvent;
use App\Event\CreatePropertyEvent;
use App\Event\UpdatePropertyEvent;
use App\Event\DeletePropertyEvent;
use App\Event\CreateFeatureFlagEvent;
use App\Event\UpdateFeatureFlagEvent;
use App\Event\DeleteFeatureFlagEvent;

#[AsEventListener(event: CreateUserEvent::class, method: 'onCreateUser')]
#[AsEventListener(event: UpdateUserEvent::class, method: 'onUpdateUser')]
#[AsEventListener(event: DeleteUserEvent::class, method: 'onDeleteUser')]
#[AsEventListener(event: CreateTenantEvent::class, method: 'onCreateTenant')]
#[AsEventListener(event: UpdateTenantEvent::class, method: 'onUpdateTenant')]
#[AsEventListener(event: DeleteTenantEvent::class, method: 'onDeleteTenant')]
#[AsEventListener(event: CreatePropertyEvent::class, method: 'onCreateProperty')]
#[AsEventListener(event: UpdatePropertyEvent::class, method: 'onUpdateProperty')]
#[AsEventListener(event: DeletePropertyEvent::class, method: 'onDeleteProperty')]
#[AsEventListener(event: CreateFeatureFlagEvent::class, method: 'onCreateFeatureFlag')]
#[AsEventListener(event: UpdateFeatureFlagEvent::class, method: 'onUpdateFeatureFlag')]
#[AsEventListener(event: DeleteFeatureFlagEvent::class, method: 'onDeleteFeatureFlag')]
class AuditLogListener
{
    private AuditRepository $auditRepository;
    private Security $security;

    public function __construct(AuditRepository $auditRepository, Security $security)
    {
        $this->auditRepository = $auditRepository;
        $this->security = $security;
    }

    public function onCreateUser(CreateUserEvent $event): void
    {
        $this->logAudit(
            "Created user: {$event->getUser()->getEmail()}",
            'User',
            $event->getUser()->getId(),
            'create',
            []
        );
    }

    public function onUpdateUser(UpdateUserEvent $event): void
    {
        $this->logAudit(
            "Updated user: {$event->getUser()->getEmail()}",
            'User',
            $event->getUser()->getId(),
            'update',
            ['changes' => $event->getChanges()]
        );
    }

    public function onDeleteUser(DeleteUserEvent $event): void
    {
        $this->logAudit(
            "Deleted user: {$event->getUser()->getEmail()}",
            'User',
            $event->getUser()->getId(),
            'delete',
            []
        );
    }

    public function onCreateTenant(CreateTenantEvent $event): void
    {
        $this->logAudit(
            "Created tenant: {$event->getTenant()->getName()}",
            'Tenant',
            $event->getTenant()->getId(),
            'create',
            ['changes' => $event->getChanges()]
        );
    }

    public function onUpdateTenant(UpdateTenantEvent $event): void
    {
        $this->logAudit(
            "Updated tenant: {$event->getTenant()->getName()}",
            'Tenant',
            $event->getTenant()->getId(),
            'update',
            ['changes' => $event->getChanges()]
        );
    }

    public function onDeleteTenant(DeleteTenantEvent $event): void
    {
        $this->logAudit(
            "Deleted tenant: {$event->getTenant()->getName()}",
            'Tenant',
            $event->getTenant()->getId(),
            'delete',
            []
        );
    }

    public function onCreateProperty(CreatePropertyEvent $event): void
    {
        $this->logAudit(
            "Created property: {$event->getProperty()->getName()}",
            'Property',
            $event->getProperty()->getId(),
            'create',
            ['changes' => $event->getChanges()]
        );
    }

    public function onUpdateProperty(UpdatePropertyEvent $event): void
    {
        $this->logAudit(
            "Updated property: {$event->getProperty()->getName()}",
            'Property',
            $event->getProperty()->getId(),
            'update',
            ['changes' => $event->getChanges()]
        );
    }

    public function onDeleteProperty(DeletePropertyEvent $event): void
    {
        $this->logAudit(
            "Deleted property: {$event->getProperty()->getName()}",
            'Property',
            $event->getProperty()->getId(),
            'delete',
            []
        );
    }

    public function onCreateFeatureFlag(CreateFeatureFlagEvent $event): void
    {
        $this->logAudit(
            "Created feature flag: {$event->getFeatureFlag()->getName()}",
            'FeatureFlag',
            $event->getFeatureFlag()->getId(),
            'create',
            ['changes' => $event->getChanges()]
        );
    }

    public function onUpdateFeatureFlag(UpdateFeatureFlagEvent $event): void
    {
        $featureFlag = $event->getFeatureFlag();

        $this->logAudit(
            "Updated feature flag: {$featureFlag->getName()}",
            'FeatureFlag',
            $featureFlag->getId(),
            'update',
            ['changes' => $event->getChanges()]
        );
    }

    public function onDeleteFeatureFlag(DeleteFeatureFlagEvent $event): void
    {
        $featureFlag = $event->getFeatureFlag();

        $this->logAudit(
            "Deleted feature flag: {$featureFlag->getName()}",
            'FeatureFlag',
            $featureFlag->getId(),
            'delete',
            []
        );
    }

    public function logAudit(string $title, string $resourceType, string $resourceId, string $action, array $context): void
    {
        $authenticatedUser = $this->security->getUser();

        $audit = new Audit();
        $audit->setUser($authenticatedUser);
        $audit->setTitle($title);
        $audit->setResourceType($resourceType);
        $audit->setResourceId($resourceId);
        $audit->setAction($action);
        $audit->setContext($context);
        $audit->setRegisteredAt(new DateTimeImmutable());

        $this->auditRepository->save($audit);
    }
}