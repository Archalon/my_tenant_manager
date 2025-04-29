<?php

namespace App\EventListener;

use App\Entity\Audit;
use App\Event\AuditLogEvent;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Repository\AuditRepository;

#[AsEventListener(event: AuditLogEvent::NAME, method: 'onAuditLog')]
class AuditLogListener
{
    private AuditRepository $auditRepository;
    private Security $security;

    public function __construct(AuditRepository $auditRepository, Security $security)
    {
        $this->auditRepository = $auditRepository;
        $this->security = $security;
    }

    public function onAuditLog(AuditLogEvent $event): void
    {
        $authenticatedUser = $this->security->getUser();

        $audit = new Audit();
        $audit->setUser($authenticatedUser);
        $audit->setTitle($event->getTitle());
        $audit->setResourceType($event->getResourceType());
        $audit->setResourceId($event->getResourceId());
        $audit->setAction($event->getAction());
        $audit->setContext($event->getContext());
        $audit->setRegisteredAt(new DateTimeImmutable());

        $this->auditRepository->save($audit);
    }
}