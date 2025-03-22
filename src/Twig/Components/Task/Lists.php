<?php

namespace App\Twig\Components\Task;

use App\Entity\Section;
use App\Repository\TaskRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Lists
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?Section $section = null;

    #[LiveProp(writable: true, updateFromParent: true)]
    public string $query = '';

    #[LiveProp(writable: true)]
    public string $status = '';

    #[LiveProp(writable: true)]
    public string $priority = '';

    #[LiveProp(writable: true)]
    public int $archived = -1;

    public function __construct(private TaskRepository $taskRepository)
    {}

    public function mount(?Section $section = null): void
    {
        $this->section = $section;
    }

    public function getTasks(): iterable
    {
        return $this->taskRepository->filterListTasks(
            $this->section, 
            $this->query, 
            $this->status, 
            $this->priority, 
            $this->archived
        );
    }
}
