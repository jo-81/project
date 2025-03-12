<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\Capability;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class UserVoter extends Voter
{
    public const CAN = 'CAN_ADD_PROJECT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CAN]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case self::CAN:

                if ($user->getCapability() == Capability::VIP) {
                    return true;
                }

                return false;
                break;
        }

        return false;
    }
}
