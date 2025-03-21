<?php

namespace App\Controller;

use App\Entity\Project;
use App\Service\ProjectService;
use Symfony\Bundle\SecurityBundle\Security;
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
}
