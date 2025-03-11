<?php

namespace App\Twig\Components;

use App\Repository\ProjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class ListProjects
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp(writable: true)]
    public int $page = 1;

    public function __construct(
        private Security $security,
        private ProjectRepository $projectRepository,
        private PaginatorInterface $paginator
    ) {}

    public function getProjects()
    {
        $query = $this->projectRepository->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%'.$this->query.'%')
            ->getQuery();

        return $this->paginator->paginate($query, $this->page, 5);
    }

    public function prevPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage(): void
    {
        $this->page++;
    }

    #[LiveAction]
    public function updatePage(int $page): void
    {
        $this->page = max(1, $page);
    }
}
