<?php

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectService
{
    public function __construct(private EntityManagerInterface $em, private Security $security)
    {
    }

    public function register(Project $project): void
    {
        $user = $this->security->getUser();
        $project->setOwner($user);

        $this->em->persist($project);
        $this->em->flush();
    }

    public function remove(Project $project): void
    {
        $this->em->remove($project);
        $this->em->flush();
    }
}
