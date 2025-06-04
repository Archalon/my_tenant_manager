<?php

namespace App\Repository;

use App\Entity\Audit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Audit>
 */
class AuditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audit::class);
    }

    public function save(Audit $audit): void
    {
        $this->getEntityManager()->persist($audit);
        $this->getEntityManager()->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['registeredAt' => 'DESC']); // Return audits ordered by the registeredAt field, descending.
    }

    public function findAudit(int $id): ?Audit
    {
        return $this->findOneBy(['id' => $id]);
    }
}
