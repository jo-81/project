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

    public function register(Section $section): void
    {
        $position = $this->sectionRepository->findPosition($section->getProject());
        $section->setPosition($position);

        $this->em->persist($section);
        $this->em->flush();
    }

    public function remove(Section $section): void
    {
        $this->em->remove($section);
        $this->em->flush();
    }
}