<?php

namespace App\Twig\Components\Section;

use App\Entity\Project;
use App\Repository\SectionRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
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

    public function getSections()
    {
        return $this->sectionRepository->filterListSections(
            $this->project, 
            $this->query, 
            $this->displaySection
        );
    }

    public function numberSection()
    {
        return count($this->getSections());
    }

    #[LiveListener('section:register')]
    public function onRefreshSection()
    {}
}
