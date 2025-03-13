<?php

namespace App\Twig\Components\Activity;

use App\Entity\User;
use App\Repository\ActivityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class Lists
{
    use DefaultActionTrait;

    public User $user;

    public string $entityName;

    #[LiveProp(writable: true)]
    public int $page = 1;

    public function __construct(
        private ActivityRepository $activityRepository,
        private PaginatorInterface $paginator
    )
    {}

    public function getActivities()
    {
        try {
            $qb = $this->activityRepository->findActivitiesByUser($this->user, $this->entityName);

            return $this->paginator->paginate(
                $qb,
                $this->page,
                3
            );
        } catch (\Exception $e) {
            return [];
        }
    }
}
