<?php

namespace App\Twig\Components\Bootstrap;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Modal
{
    public string $title;

    public string $id;
}
