<?php

namespace App\Repository;

use App\Entity\FeatureFlag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeatureFlag>
 */
class FeatureFlagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatureFlag::class);
    }

    public function save(FeatureFlag $featureFlag): void
    {
        $this->getEntityManager()->persist($featureFlag);
        $this->getEntityManager()->flush();
    }

    public function delete(FeatureFlag $featureFlag): void
    {
        $this->getEntityManager()->remove($featureFlag);
        $this->getEntityManager()->flush();
    }

    public function findByName(string $name): ?FeatureFlag
    {
        return $this->findOneBy(['name' => $name]);
    }
}
