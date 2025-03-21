<?php

namespace App\Twig\Components;

use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;
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

    #[LiveProp(writable: true)]
    public string $actionName = 'Ajouter';

    #[LiveProp(writable: true)]
    public string $methodName;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ProjectFormType::class, $this->initialFormData);
    }

    #[PostMount]
    public function postMount(): void
    {
        $this->actionName = is_null($this->initialFormData) ? 'Ajouter' : 'Modifier';
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
        $this->addFlash('success', 'Votre projet a bien été enregistré.');

        return $this->redirectToRoute('project.list');
    }

    #[LiveAction]
    public function update(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour créer un projet.');
        }

        if (!$this->isGranted('PROJECT_EDIT', $this->initialFormData)) {
            throw new AccessDeniedException('Vous ne pouvez pas modifier ce projet.');
        }

        $this->submitForm();

        $project = $this->getForm()->getData();

        $em->persist($project);
        $em->flush();

        $this->resetForm();
        $this->addFlash('success', 'Votre projet a bien été modifié.');

        return $this->redirectToRoute('project.single', ['id' => $project->getId()]);
    }
}
