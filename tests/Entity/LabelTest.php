<?php

namespace App\Tests\Entity;

use App\Entity\Label;
use App\Tests\Traits\EntityTrait;
use App\Tests\Traits\ValidatorTrait;
use App\Repository\ProjectRepository;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LabelTest extends KernelTestCase
{
    use ReloadDatabaseTrait;
    use EntityTrait;
    use ValidatorTrait;

    private Label $label;

    private static ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::$validator = static::getContainer()->get('validator');

        $project = $this->getEntity(ProjectRepository::class, ['id' => 1]);

        $this->label = (new Label())
            ->setName('labelName')
            ->setColor('#000')
            ->setProject($project)
        ;
    }

    /**
     * testGoodEntity.
     */
    public function testGoodEntity(): void
    {
        $this->assertCount(0, self::$validator->validate($this->label));
    }

    public function testLabelName(): void
    {
        $label = $this->label;
        $label->setName('');

        $this->assertCount(1, self::$validator->validate($label));
    }

    /**
     * testLabelColor.
     */
    public function testLabelColor(): void
    {
        $label = $this->label;

        $label->setColor('');
        $this->assertCount(1, self::$validator->validate($label));

        $label->setColor('color');
        $this->assertCount(1, self::$validator->validate($label));
    }
}
