<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
        
    /**
     * testRouteProfileExistWhenUserLogged
     * 
     * @dataProvider getDataRouteProfile
     *
     * @return void
     */
    public function testRouteProfileExistWhenUserLogged(string $path): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $this->client->loginUser($testUser);

        $this->client->request('GET', $path);

        self::assertResponseIsSuccessful();
    }

    /**
     * testRouteProfileExistWhenUserNotLogged
     * 
     * @dataProvider getDataRouteProfile
     * 
     * @return void
     */
    public function testRouteProfileExistWhenUserNotLogged(string $path): void
    {
        $this->client->request('GET', $path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/connexion');
    }
    
    /**
     * getDataRouteProfile
     *
     * @return array
     */
    public static function getDataRouteProfile(): array
    {
        return [
            ['/profile'],
            ['/profile/edit'],
        ];
    }

    public function testEditProfile(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/profile/edit');
        $this->client->submitForm('Modifier', [
            'user_edit[username]' => 'admin1',
            'user_edit[email]' => 'admin1@domaine.com',
        ]);

        $userEdit = $userRepository->findOneByUsername('admin1');

        self::assertResponseRedirects('/profile');
        self::assertEquals("admin1", $userEdit->getUsername());
        self::assertEquals("admin1@domaine.com", $userEdit->getEmail());
    }
}