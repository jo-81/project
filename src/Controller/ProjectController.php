<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\ProjectService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
final class ProjectController extends AbstractController
{
    public function __construct(private Security $security, private ProjectService $projectService)
    {
    }

    #[Route('/projects', name: 'project.list', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('project/index.html.twig');
    }

    #[IsGranted('PROJECT_SHOW', 'project', 'Vous ne pouvez pas accéder à ce projet.')]
    #[Route('/projects/{id}', name: 'project.single', methods: ['GET', 'POST'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[IsGranted('PROJECT_REMOVE', 'project', 'Vous ne pouvez pas accéder à ce projet.')]
    #[Route('/projects/remove/{id}', name: 'project.remove', methods: ['DELETE'])]
    public function remove(Project $project, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Authentification requise');

            return $this->redirectToRoute('login');
        }

        if (!$this->isCsrfTokenValid('remove-project', $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide');

            return $this->redirectToRoute('project.single', ['id' => $project->getId()]);
        }

        try {
            $this->projectService->remove($project);
            $this->addFlash('success', 'Votre projet a bien été supprimé avec succès.');

            return $this->redirectToRoute('project.list');
        } catch (EntityNotFoundException $e) {
            $this->addFlash('danger', 'Projet introuvable');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur technique : '.$e->getMessage());
        }

        return $this->redirectToRoute('project.list');
    }
}
