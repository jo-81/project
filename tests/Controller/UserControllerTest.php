<?php

namespace App\Tests\Controller;

use App\Tests\Traits\EntityTrait;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

final class UserControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;
    use EntityTrait;
    
    /**
     * testIndexWhenUserNotLogged
     *
     * @return void
     */
    public function testIndexWhenUserNotLogged(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/users');

        self::assertResponseStatusCodeSame(302);
    }
    
    /**
     * testIndexWhenUserLoggedWithRoleNotAdmin
     *
     * @return void
     */
    public function testIndexWhenUserLoggedWithRoleNotAdmin(): void
    {
        $client = static::createClient();
        $user = $this->get(UserRepository::class, ['username' => 'username']);
        $client->loginUser($user);
        $client->request('GET', '/admin/users');

        self::assertResponseStatusCodeSame(403);
    }
    
    /**
     * testIndexWhenUserLoggedWithRoleAdmin
     *
     * @return void
     */
    public function testIndexWhenUserLoggedWithRoleAdmin(): void
    {
        $client = static::createClient();
        $user = $this->get(UserRepository::class, ['username' => 'admin']);
        $client->loginUser($user);
        $client->request('GET', '/admin/users');

        self::assertResponseIsSuccessful();
    }
}
