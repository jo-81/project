<?php

namespace App\Twig\Components;

use App\Entity\Label;
use App\Entity\Project;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class LabelSingle
{
    public Label $label;

    public Project $project;
}
