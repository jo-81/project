<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findByUserAndQuery(string $query, User $owner, int $archived): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.owner = :owner')
            ->setParameter('owner', $owner)
        ;

        if ($archived != -1) {
            $qb
                ->andWhere('p.archived = :archived')
                ->setParameter('archived', $archived)
            ;
        }

        if (! empty($query)) {
            $qb
                ->andWhere('p.name LIKE :query')
                ->setParameter('query', '%' . $query . '%')
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
