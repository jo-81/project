<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Activity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findActivitiesByUser(User $user, ?string $entityName = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'ASC')
        ;

        if (!is_null($entityName)) {
            $qb->andWhere('a.entityName = :entityName')->setParameter('entityName', $entityName);
        }

        return $qb->getQuery()->getResult();
    }
}
