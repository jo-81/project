<?php

namespace App\Tests\Traits;

use App\Entity\User;
use App\Repository\UserRepository;

trait UserLoginTrait
{
    public function connexion(array $criteria): ?User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        return $userRepository->findOneBy($criteria);
    }
}