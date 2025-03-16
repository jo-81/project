<?php

namespace App\Twig\Components;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class UserList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public string $query = '';

    #[LiveProp(writable: true, url: true)]
    public int $page = 1;

    #[LiveProp(writable: true, url: true)]
    public $capability = '';

    public function __construct(private UserRepository $userRepository, private PaginatorInterface $paginator)
    {
    }

    public function getUsers()
    {
        try {
            return $this->paginator->paginate(
                $this->userRepository->findByUsers($this->query, $this->capability),
                $this->page,
                5
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
