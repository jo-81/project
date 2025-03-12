<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class RegistrationControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();
        /* @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);
    }

    /**
     * testRouteLoginExist.
     */
    public function testRouteLoginExist(): void
    {
        $this->client->request('GET', '/inscription');

        self::assertResponseIsSuccessful();
    }

    /**
     * testSuccessRegistration.
     */
    public function testSuccessRegistration(): void
    {
        $this->client->request('GET', '/inscription');
        $this->client->submitForm('Inscription', [
            'registration_form[username]' => 'user_name33',
            'registration_form[email]' => 'user_name33@example.com',
            'registration_form[plainPassword]' => 'Azerty4567',
            'registration_form[agreeTerms]' => true,
        ]);

        // Ensure the response redirects after submitting the form, the user exists, and is not verified
        self::assertResponseRedirects('/projects');

        /** @var User */
        $user = $this->userRepository->findOneBy(['username' => 'user_name33']);
        self::assertCount(4, $this->userRepository->findAll());
        self::assertFalse($user->isVerified());
    }
}
