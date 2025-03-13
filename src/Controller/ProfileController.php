<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

final class ProfileController extends AbstractController
{
    public function __construct(private Security $security)
    {}

    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->security->getUser(),
        ]);
    }
}
