<?php

namespace App\Twig\Components;

use App\Entity\Label;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class LabelSingle
{
    public Label $label;
}
