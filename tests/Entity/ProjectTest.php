<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Repository\UserRepository;

class ProjectTest extends AbstractEntityTest
{
    public function getObject(): object
    {
        $owner = $this->getEntity(UserRepository::class, ['id' => 1]);

        $project = new Project();
        $project
            ->setName('Mon projet')
            ->setSlug('mon-projet')
            ->setDescription('<p>La description de mon projet</p>')
            ->setArchived(false)
            ->setOwner($owner)
        ;

        return $project;
    }

    public function testProjectName(): void
    {
        $project = $this->getObject();
        $project->setName('');

        $this->assertCount(1, $this->getErrors(self::$validator, $project));
    }
}
