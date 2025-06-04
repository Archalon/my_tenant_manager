<?php

namespace App\Service;

use App\Entity\Audit;
use App\Entity\User;
use App\Repository\AuditRepository;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;

class AuditService
{
    public function __construct(
        private AuditRepository $auditRepository
    ) {}

    public function getAllAudits(): array
    {
        return $this->auditRepository->findAll();
    }

    public function getAuditById(int $id): ?Audit
    {
        return $this->auditRepository->findAudit($id);
    }
}