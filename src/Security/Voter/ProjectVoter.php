<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ProjectVoter extends Voter
{
    public const EDIT = 'PROJECT_EDIT';

    public const SHOW = 'PROJECT_SHOW';

    public const REMOVE = 'PROJECT_REMOVE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::SHOW, self::REMOVE]) && $subject instanceof Project;
    }

    /**
     * voteOnAttribute.
     *
     * @param Project $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getOwner() == $user;

                break;

            case self::SHOW:
                return $subject->getOwner() == $user;

                break;

            case self::REMOVE:
                return $subject->getOwner() == $user;

                break;
        }

        return false;
    }
}
