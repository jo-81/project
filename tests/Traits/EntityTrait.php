<?php

namespace App\Tests\Traits;

trait EntityTrait
{
    public function getEntity(string $repositoryName, array $criteria): ?object
    {
        $repository = static::getContainer()->get($repositoryName);

        return $repository->findOneBy($criteria);
    }
}
