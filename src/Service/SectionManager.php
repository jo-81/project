<?php

namespace App\Service;

use App\Entity\Section;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SectionManager
{
    public function __construct(private EntityManagerInterface $em, private SectionRepository $sectionRepository)
    {
    }

    public function register(Section $section)
    {
        $position = $this->sectionRepository->findPosition($section->getProject());
        $section->setPosition($position);

        $this->em->persist($section);
        $this->em->flush();
    }
}