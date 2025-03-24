<?php

namespace App\Controller;

use App\Entity\Section;
use App\Service\SectionManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class SectionController extends AbstractController
{
    public function __construct(private SectionManager $sectionManager)
    {
        
    }

    #[Route('/sections/remove/{id}', name: 'section.remove', methods: ['DELETE'])]
    public function remove(Section $section, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Authentification requise');

            return $this->redirectToRoute('login');
        }

        $project = $section->getProject();
        if (!$this->isCsrfTokenValid('remove-section', $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide');

            return $this->redirectToRoute('project.single', ['id' => $project->getId()]);
        }

        if (! $this->isGranted("PROJECT_EDIT", $project)) {
            throw new AccessDeniedException('Accès refusé pour ce projet');
        }

        try {
            $this->sectionManager->remove($section);
            $this->addFlash('success', 'Votre section a bien été supprimée.');
        } catch (EntityNotFoundException $e) {
            $this->addFlash('danger', 'Section introuvable');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur technique : '.$e->getMessage());
        }

        return $this->redirectToRoute('project.single', ['id' => $project->getId()]);
    }
}
