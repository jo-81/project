<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\EntityTrait;
use App\Repository\UserRepository;
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

    public function testAccessPageWhenUserNotLogged(): void
    {
        $this->client->request('GET', '/projects');

        self::assertResponseRedirects('/connexion');
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
}
