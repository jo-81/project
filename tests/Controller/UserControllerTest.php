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
     * testIndexWhenUserNotLogged.
     *
     * @dataProvider getDatasForUserRoute
     */
    public function testIndexWhenUserNotLogged(string $path): void
    {
        $client = static::createClient();
        $client->request('GET', $path);

        self::assertResponseStatusCodeSame(302);
    }

    /**
     * testIndexWhenUserLoggedWithRoleNotAdmin.
     *
     * @dataProvider getDatasForUserRoute
     */
    public function testIndexWhenUserLoggedWithRoleNotAdmin(string $path): void
    {
        $client = static::createClient();
        $user = $this->getEntity(UserRepository::class, ['username' => 'username']);
        $client->loginUser($user);
        $client->request('GET', $path);

        self::assertResponseStatusCodeSame(403);
    }

    public static function getDatasForUserRoute(): array
    {
        return [
            ['/admin/users'],
            ['/admin/users/1'],
        ];
    }

    /**
     * testIndexWhenUserLoggedWithRoleAdmin.
     */
    public function testIndexWhenUserLoggedWithRoleAdmin(): void
    {
        $client = static::createClient();
        $user = $this->getEntity(UserRepository::class, ['username' => 'admin']);
        $client->loginUser($user);
        $client->request('GET', '/admin/users');

        self::assertResponseIsSuccessful();
    }

    public function testSingleUserWhenExist(): void
    {
        $client = static::createClient();
        $user = $this->getEntity(UserRepository::class, ['username' => 'admin']);
        $client->loginUser($user);
        $client->request('GET', '/admin/users/1');

        self::assertResponseIsSuccessful();
    }

    public function testSingleUserWhenNotExist(): void
    {
        $client = static::createClient();
        $user = $this->getEntity(UserRepository::class, ['username' => 'admin']);
        $client->loginUser($user);
        $client->request('GET', '/admin/users/100');

        self::assertResponseRedirects('/admin/users');
    }
}
