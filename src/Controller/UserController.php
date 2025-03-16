<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'user.list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
}
