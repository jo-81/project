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
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent]
final class LabelFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Label $initialFormData = null;

    #[LiveProp]
    public ?Project $project = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(LabelType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        $this->submitForm();

        /** @var Label $label */
        $label = $this->getForm()->getData();
        $label->setProject($this->project);

        try {
            $entityManager->persist($label);
            $entityManager->flush();

            $this->addFlash('success', "L'étiquette a bien été ajoutée.");
        } catch (ORMException $e) {
            $this->addFlash('danger', "Une erreur est survenue lors de l'ajout de l'étiquette : ".$e->getMessage());
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur inattendue est survenue.');
        }

        return $this->redirectToRoute('project.single', [
            'id' => $this->project->getId(),
        ]);
    }
}
