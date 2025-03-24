<?php

namespace App\Twig\Components\Section;

use App\Entity\Project;
use App\Repository\SectionRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Lists
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?Project $project = null;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp(writable: true)]
    public int $displaySection = 0;

    public function __construct(private SectionRepository $sectionRepository)
    {}

    public function getSections(): iterable
    {
        return $this->sectionRepository->filterListSections(
            $this->project, 
            $this->query, 
            $this->displaySection
        );
    }

    public function numberSection(): int
    {
        return count($this->getSections());
    }

    public function getAllSections(): iterable
    {
        return $this->sectionRepository->findBy(['project' => $this->project]);
    }
}
