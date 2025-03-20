<?php

namespace App\Tests\Entity;

use App\Tests\Traits\EntityTrait;
use App\Tests\Traits\ValidatorTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractEntityTest extends KernelTestCase
{
    use ValidatorTrait;
    use ReloadDatabaseTrait;
    use EntityTrait;

    protected static ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::$validator = static::getContainer()->get('validator');
    }

    abstract public function getObject(): object;

    /**
     * testGoodEntity.
     */
    public function testGoodEntity(): void
    {
        $this->assertCount(0, $this->getErrors(self::$validator, $this->getObject()));
    }
}
