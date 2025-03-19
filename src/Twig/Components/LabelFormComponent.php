<?php

namespace App\Twig\Components;

use App\Entity\Label;
use App\Entity\Project;
use App\Form\LabelType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
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

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(LabelType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        if (!$this->isGranted('PROJECT_EDIT', $this->project)) {
            throw new AccessDeniedException('Accès refusé pour ce projet');
        }

        $this->submitForm();

        try {
            $label = $this->getForm()->getData();
            $label->setProject($this->project);

            $entityManager->persist($label);
            $entityManager->flush();
            $this->resetForm();
        } catch (ORMException $e) {
            $this->addFlash('danger', 'Un problème est survenue.');
        }

        $this->resetForm();
        $this->emitUp('emit_label_management');
    }
}
