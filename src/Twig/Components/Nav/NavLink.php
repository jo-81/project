<?php

namespace App\Twig\Components\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class NavLink
{
    public string $label = "";

    public string $path = "";

    public bool $isActive = false;
}
