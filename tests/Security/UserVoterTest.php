<?php

namespace App\Tests\Security;

use App\Security\Voter\UserVoter;
use App\Tests\Traits\EntityTrait;
use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserVoterTest extends KernelTestCase
{
    use EntityTrait;
    use ReloadDatabaseTrait;

    public function testVoteUserWithVipCapability(): void
    {
        $voter = new UserVoter();
        $user = $this->getEntity(UserRepository::class, ['id' => 1]);
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $user, [UserVoter::CAN]));
    }
}
