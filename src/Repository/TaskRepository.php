<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Section;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function filterListTasks(Section $section, string $query, string $status, string $priority, string $archived)
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.section = :section')
            ->setParameter('section', $section)
        ;

        if (! empty($query)) {
            $qb
                ->andWhere('t.name LIKE :query')
                ->setParameter('query', '%'.$query.'%')
            ;
        }

        if (! empty($status)) {
            $qb
                ->andWhere('t.status = :status')
                ->setParameter('status', $status)
            ;
        }

        if (! empty($priority)) {
            $qb
                ->andWhere('t.priority = :priority')
                ->setParameter('priority', $priority)
            ;
        }

        if (in_array($archived, [0, 1])) {
            $qb
                ->andWhere('t.archived = :archived')
                ->setParameter('archived', $archived)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * findInformationsTasksBySection.
     *
     * [
     *  'numberTasks' => 5,
     *  'numberTasksDone => 3,
     * ]
     *
     * @param Project $section
     */
    public function findInformationsTasksBySection(Section $section)
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.section = :section')
            ->setParameter('section', $section)

            // ->andWhere('t.status = :status')
            // ->setParameter('status', Status::DONE)
        ;

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
