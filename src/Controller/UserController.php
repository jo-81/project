<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/admin/users', name: 'user.list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/admin/users/{id}', name: 'user.single', methods: ['GET'])]
    public function single(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            $this->addFlash('danger', \sprintf("L'utilisateur avec id: %d n'existe pas", $id));

            return $this->redirectToRoute('user.list');
        }

        return $this->render('user/single.html.twig', ['user' => $user]);
    }
}
