<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProjectController extends AbstractController
{
    public function __construct(private Security $security, private ProjectRepository $projectRepository)
    {
    }

    #[Route('/projects', name: 'project.list', methods: ['GET'])]
    public function index(): Response
    {
        /** @var User */
        $user = $this->security->getUser();

        return $this->render('project/index.html.twig', [
            'number_project' => count($user->getProjects()),
        ]);
    }

    #[Route('/projects/{id}', name: 'project.single', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
