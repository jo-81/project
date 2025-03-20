<?php

namespace App\Twig\Components;

use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[AsLiveComponent]
final class ProjectFormTypeComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['name', 'description'])]
    public ?Project $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ProjectFormType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour créer un projet.');
        }

        if (!$this->isGranted('CAN_ADD_PROJECT', $user)) {
            $this->addFlash('danger', 'Vous avez atteint la limite pour la création de projet.');

            return $this->redirectToRoute('project.list');
        }

        $this->submitForm();

        $project = $this->getForm()->getData();
        $project->setOwner($user);

        $em->persist($project);
        $em->flush();

        $this->resetForm();
        $this->addFlash('success', 'Votre projet a bien été ajouté.');

        return $this->redirectToRoute('project.list');
    }
}
