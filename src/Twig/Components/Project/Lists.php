<?php

namespace App\Twig\Components\Project;

use App\Repository\ProjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Lists
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public string $query = '';

    #[LiveProp(writable: true, url: true)]
    public int $page = 1;

    #[LiveProp(writable: true, url: true)]
    public int $archived = -1;

    public function __construct(
        private Security $security,
        private ProjectRepository $projectRepository,
        private PaginatorInterface $paginator
    ) {
    }

    public function getProjects()
    {
        try {
            $user = $this->security->getUser();
            $qb = $this->projectRepository->findByUserAndQuery($this->query, $user, $this->archived);

            return $this->paginator->paginate(
                $qb,
                $this->page,
                4
            );

        } catch (\Exception $e) {
            return [];
        }
    }

    #[LiveAction]
    public function nextPage(): void
    {
        $this->page = max(1, $this->page + 1);
    }

    #[LiveAction]
    public function prevPage(): void
    {
        $this->page = max(1, $this->page - 1);
    }

    #[LiveAction]
    public function selectedPage(#[LiveArg] int $page): void
    {
        $this->page = $page;
    }
}
