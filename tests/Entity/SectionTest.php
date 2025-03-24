<?php

namespace App\Tests\Entity;

use App\Entity\Section;
use App\Repository\ProjectRepository;

class SectionTest extends AbstractEntityTest
{
    public function getObject(): object
    {
        $project = $this->getEntity(ProjectRepository::class, ['id' => 1]);
        $section = new Section;

        $section
            ->setProject($project)
            ->setName('A new project')
            ->setDescription('A description for a new project')
        ;

        return $section; 
    }
    
    /**
     * testSectionName
     *
     * @return void
     */
    public function testSectionName(): void
    {
        $section = $this->getObject();
        $section->setName('');

        $this->assertCount(1, $this->getErrors(self::$validator, $section));
    }
}