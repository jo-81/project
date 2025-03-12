<?php

namespace App\Twig\Components\Project;

use App\Entity\Project;
use App\Repository\TaskRepository;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class Card
{
    use DefaultActionTrait;

    public ?Project $project = null;

    public function __construct(private TaskRepository $taskRepository)
    {
    }

    public function getNumberTask(): int
    {
        $sections = $this->project->getSections();
        $numberTask = 0;
        foreach ($sections as $section) {
            $numberTask += count($section->getTasks());
        }

        return $numberTask;
    }

    public function getNumberTaskCompleted()
    {
    }
}
