<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Service\UserService;
use App\Form\UserEditPasswordType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Exception\PersisterException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    public function __construct(private Security $security, private UserService $userService)
    {
    }

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
            try {
                $user = $form->getData();
                $this->userService->update($user);
                $this->addFlash('success', 'Votre profil a bien été modifié.');

                return $this->redirectToRoute('profile');
            } catch (PersisterException $th) {
                $this->addFlash('danger', 'Un problème est survenue lors de la modification de votre profil.');

                return $this->redirectToRoute('profile.edit');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profile/edit-password', name: 'profile.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request)
    {
        $user = $this->security->getUser();
        $form = $this->createForm(UserEditPasswordType::class, $user, [
            'action' => $this->generateUrl('profile.edit.password'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $form->getData();
                $this->userService->updatePassword($user);
                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

                return $this->redirectToRoute('profile');
            } catch (PersisterException $th) {
                $this->addFlash('danger', 'Un problème est survenue lors de la modification de votre mot de passe.');

                return $this->redirectToRoute('profile.edit.password');
            }
        }

        return $this->render('profile/edit-password.html.twig', [
            'form' => $form,
        ]);
    }
}
