<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\EntityTrait;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ProjectControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;
    use EntityTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * testAccessPageWhenUserNotLogged.
     *
     * @dataProvider getDataForAccessPageWhenUserNotLogged
     */
    public function testAccessPageWhenUserNotLogged(string $path): void
    {
        $this->client->request('GET', $path);

        self::assertResponseRedirects('/connexion');
    }

    public function getDataForAccessPageWhenUserNotLogged(): array
    {
        return [
            ['/projects'],
            ['/projects/1'],
        ];
    }

    /**
     * testAccessPageWhenUserLogged.
     */
    public function testAccessPageWhenUserLogged(): void
    {
        $user = $this->getEntity(UserRepository::class, ['id' => 1]);
        $this->client->loginUser($user);
        $this->client->request('GET', '/projects');

        self::assertResponseIsSuccessful();
    }

    public function testAccessPageProjectWhenUserNotOwner(): void
    {
        $user = $this->getEntity(UserRepository::class, ['id' => 1]);
        $this->client->loginUser($user);

        $this->client->request('GET', '/projects/1');
        self::assertResponseIsSuccessful();

        $this->client->request('GET', '/projects/17');
        self::assertResponseStatusCodeSame(403);
    }

    public function testRenderCapabilityUserWithCapabilityVip(): void
    {
        /** @var User */
        $user = $this->getEntity(UserRepository::class, ['id' => 1]);
        $this->client->loginUser($user);
        $this->client->request('GET', '/projects');

        $this->assertSelectorTextContains('body', "Vous n'êtes pas limité pour la création de projets.");
    }

    public function testRenderCapabilityUserWithCapabilityPremium(): void
    {
        /** @var User */
        $user = $this->getEntity(UserRepository::class, ['id' => 2]);
        $this->client->loginUser($user);
        $this->client->request('GET', '/projects');

        $this->assertSelectorTextContains('body', 'Vous êtes limité à 10 projets.');
    }

    public function testRenderCapabilityUserWithCapabilityRegister(): void
    {
        /** @var User */
        $user = $this->getEntity(UserRepository::class, ['id' => 3]);
        $this->client->loginUser($user);
        $this->client->request('GET', '/projects');

        $this->assertSelectorTextContains('body', 'Vous êtes limité à 5 projets.');
    }
    
    /**
     * testRemoveProjectWhenUserNotLogged
     *
     * @return void
     */
    public function testRemoveProjectWhenUserNotLogged(): void
    {
        $this->client->request('DELETE', '/projects/remove/1');

        self::assertResponseRedirects('/connexion');
    }
    
    /**
     * testRemoveProjectWhenUserLoggedButNotOwner
     *
     * @return void
     */
    public function testRemoveProjectWhenUserLoggedButNotOwner(): void
    {
        /** @var User */
        $user = $this->getEntity(UserRepository::class, ['id' => 2]);
        $this->client->loginUser($user);
        $this->client->request('DELETE', '/projects/remove/1');

        self::assertResponseStatusCodeSame(403);
    }
}
