<?php

namespace App\Twig\Components;

use App\Entity\Label;
use App\Entity\Project;
use App\Form\LabelType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[AsLiveComponent]
final class LabelFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public ?Label $initialFormData = null;

    #[LiveProp(writable: true)]
    public ?Project $project = null;

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(LabelType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function remove()
    {
        if (!$this->isGranted('PROJECT_EDIT', $this->project)) {
            throw new AccessDeniedException('Accès refusé pour ce projet');
        }

        $label = $this->initialFormData;
        if (is_null($label)) {
            return $this->redirectToRoute("project.single", ['id' => $this->project->getId()]);
        }

        try {
            $this->entityManager->remove($label);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            $this->addFlash('danger', 'Un problème est survenue.');
        }
        $this->addFlash('success', "L'étiquette a bien été supprimée.");

        return $this->redirectToRoute("project.single", ['id' => $this->project->getId()]);
    }

    #[LiveAction]
    public function save()
    {
        if (!$this->isGranted('PROJECT_EDIT', $this->project)) {
            throw new AccessDeniedException('Accès refusé pour ce projet');
        }

        $this->submitForm();

        try {
            $label = $this->getForm()->getData();
            $label->setProject($this->project);

            $message = is_null($label->getId()) ? "ajoutée" : "modifiée";

            $this->entityManager->persist($label);
            $this->entityManager->flush();
            $this->resetForm();
        } catch (ORMException $e) {
            $this->addFlash('danger', 'Un problème est survenue.');
        }

        $this->addFlash('success', "L'étiquette a bien été $message.");

        return $this->redirectToRoute("project.single", ['id' => $this->project->getId()]);
    }
}
