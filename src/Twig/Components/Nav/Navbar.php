<?php

namespace App\Twig\Components\Nav;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Navbar
{
    public ?string $currentRoute = null;

    public function __construct(private RequestStack $request)
    {
    }

    public function getCurrentRoute()
    {
        return $this->request->getCurrentRequest()->get('_route');
    }

    public function isRouteActive(string $path): bool
    {
        return preg_match('#'.$path.'#', $this->request->getCurrentRequest()->get('_route'));
    }
}
