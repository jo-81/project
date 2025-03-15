<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProfileControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * testRouteProfileExistWhenUserLogged.
     *
     * @dataProvider getDataRouteProfile
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
     * testRouteProfileExistWhenUserNotLogged.
     *
     * @dataProvider getDataRouteProfile
     */
    public function testRouteProfileExistWhenUserNotLogged(string $path): void
    {
        $this->client->request('GET', $path);

        self::assertResponseStatusCodeSame(302);
        self::assertResponseRedirects('/connexion');
    }

    /**
     * getDataRouteProfile.
     */
    public static function getDataRouteProfile(): array
    {
        return [
            ['/profile'],
            ['/profile/edit'],
            ['/profile/edit-password'],
        ];
    }

    /**
     * testEditProfile.
     */
    public function testEditProfile(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/profile/edit');
        $this->client->submitForm('Modifier', [
            'user_edit[email]' => 'admin1@domaine.com',
        ]);

        $userEdit = $userRepository->findOneByUsername('admin');

        self::assertResponseRedirects('/profile');
        self::assertEquals('admin1@domaine.com', $userEdit->getEmail());
    }

    public function testEditPasswordProfile(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/profile/edit-password');
        $this->client->submitForm('Modifier', [
            'user_edit_password[plainPassword][first]' => 'Azerty1234',
            'user_edit_password[plainPassword][second]' => 'Azerty1234',
        ]);

        self::assertResponseRedirects('/profile');
    }
}
