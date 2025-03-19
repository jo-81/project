<?php

namespace App\Twig\Components;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LabelList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?Project $project = null;

    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    #[LiveListener('emit_label_management')]
    public function refresh()
    {
    }
}
