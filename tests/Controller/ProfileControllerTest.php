<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
        
    /**
     * testRouteProfileExistWhenUserLogged
     *
     * @return void
     */
    public function testRouteProfileExistWhenUserLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/profile');

        self::assertResponseIsSuccessful();
    }
    
    /**
     * testRouteProfileExistWhenUserNotLogged
     *
     * @return void
     */
    public function testRouteProfileExistWhenUserNotLogged(): void
    {
        $this->client->request('GET', '/profile');

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/connexion');
    }
}