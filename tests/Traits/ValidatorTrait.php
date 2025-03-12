<?php

namespace App\Tests\Traits;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ValidatorTrait
{
    public function getErrors(ValidatorInterface $validator, object $entity): ConstraintViolationListInterface
    {
        return $validator->validate($entity);
    }
}
