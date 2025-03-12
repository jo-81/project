<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class LoginControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * testRouteLoginExist.
     */
    public function testRouteLoginExist(): void
    {
        $this->client->request('GET', '/connexion');

        self::assertResponseIsSuccessful();
    }

    /**
     * testDeniedLoginWithBadUsername.
     */
    public function testDeniedLoginWithBadUsername(): void
    {
        $this->client->request('GET', '/connexion');
        $this->client->submitForm('Connexion', [
            '_username' => 'admin789',
            '_password' => '0',
        ]);

        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');
    }

    /**
     * testDeniedLoginWithBadPassword.
     */
    public function testDeniedLoginWithBadPassword(): void
    {
        $this->client->request('GET', '/connexion');
        $this->client->submitForm('Connexion', [
            '_username' => 'admin',
            '_password' => '1234',
        ]);

        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');
    }

    /**
     * testSuccessLogin.
     */
    public function testSuccessLogin(): void
    {
        $this->client->request('GET', '/connexion');
        $this->client->submitForm('Connexion', [
            '_username' => 'admin',
            '_password' => '0',
        ]);

        self::assertResponseRedirects('/projects');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertSelectorTextContains('.toast.text-bg-success', 'Bienvenue admin, vous êtes connecté !');
        self::assertResponseIsSuccessful();
    }
}
