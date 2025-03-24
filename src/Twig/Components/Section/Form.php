<?php

namespace App\Twig\Components\Section;

use App\Entity\Project;
use App\Entity\Section;
use App\Form\SectionFormType;
use App\Service\SectionManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[AsLiveComponent]
final class Form extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(writable: true)]
    public ?Section $initialFormData = null;

    #[LiveProp(writable: true)]
    public ?Project $project = null;

    public function __construct(private SectionManager $sectionManager)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(SectionFormType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save()
    {
        if (!$this->isGranted('PROJECT_EDIT', $this->project)) {
            throw new AccessDeniedException('Accès refusé pour ce projet');
        }

        $this->submitForm();

        try {
            $section = $this->getForm()->getData();
            $section->setProject($this->project);

            $this->sectionManager->register($section);

            $this->resetForm();
            $this->addFlash('success', "L'étiquette a bien été ajouté.");
        } catch (ORMException $e) {
            $this->addFlash('danger', 'Un problème est survenue.');
        }

        return $this->redirectToRoute("project.single", ['id' => $this->project->getId()]);
    }
}
