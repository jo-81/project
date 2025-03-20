<?php

namespace App\Security\Voter;

use App\Entity\User;
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
                return $this->canRegisterProject($user);

                break;
        }

        return false;
    }

    /**
     * canRegisterProject.
     */
    private function canRegisterProject(User $user): bool
    {
        if ('VIP' == $user->getCapability()->name) {
            return true;
        }

        $limitCapability = $user->getCapability()->getProjectLimit();
        $numberUserProject = count($user->getProjects());

        return $numberUserProject < $limitCapability && $numberUserProject != $limitCapability;
    }
}
