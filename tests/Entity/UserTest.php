<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Traits\ValidatorTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    use ValidatorTrait;
    use ReloadDatabaseTrait;

    private User $user;

    private static ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::$validator = static::getContainer()->get('validator');

        $this->user = (new User())
            ->setUsername('utilisateur_32')
            ->setEmail('utilisateur_32@domaine.fr')
            ->setPassword('Azerty1234')
        ;
    }

    /**
     * testGoodEntity.
     */
    public function testGoodEntity(): void
    {
        $this->assertCount(0, self::$validator->validate($this->user));
    }

    /**
     * testUsername.
     */
    public function testUsername(): void
    {
        // Unique
        $this->user->setUsername('admin');
        $this->assertCount(1, self::$validator->validate($this->user));

        // Bad format : white space
        $this->user->setUsername('user name');
        $this->assertCount(1, self::$validator->validate($this->user));

        // Bad format : min 4 caracters
        $this->user->setUsername('use');
        $this->assertCount(1, self::$validator->validate($this->user));
    }

    /**
     * testEmail.
     */
    public function testEmail(): void
    {
        // Unique
        $this->user->setEmail('admin@domaine.fr');
        $this->assertCount(1, self::$validator->validate($this->user));

        // is Email
        $this->user->setEmail('admi');
        $this->assertCount(1, self::$validator->validate($this->user));
    }

    /**
     * testPassword.
     */
    public function testPassword(): void
    {
        // Lenght : min 8 caracters
        $this->user->setPlainPassword('azerty');
        $this->assertCount(1, self::$validator->validate($this->user));

        // Majuscule
        $this->user->setPlainPassword('password');
        $this->assertCount(1, self::$validator->validate($this->user));

        // Number
        $this->user->setPlainPassword('Password');
        $this->assertCount(1, self::$validator->validate($this->user));
    }
}
