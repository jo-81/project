<?php

namespace App\Twig\Components;

use App\Entity\Project;
use App\Repository\LabelRepository;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LabelList
{
    use DefaultActionTrait;

    public ?Project $project = null;

    public function __construct(private LabelRepository $labelRepository)
    {
    }

    public function getLabels()
    {
        return $this->labelRepository->findAll(['project' => $this->project]);
    }
}
