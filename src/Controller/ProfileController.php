<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class ProfileController extends AbstractController
{
    public function __construct(private Security $security, private UserService $userService)
    {}

    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->security->getUser(),
        ]);
    }

    #[Route('/profile/edit', name: 'profile.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $user = $this->security->getUser();

        $form = $this->createForm(UserEditType::class, $user, [
            'action' => $this->generateUrl('profile.edit'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->userService->update($user);

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
