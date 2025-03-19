<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use App\Enum\Capability;
use Twig\Extension\RuntimeExtensionInterface;

class UserCapabilityExtensionRuntime implements RuntimeExtensionInterface
{
    public function doUserCapability(User $user): string
    {
        if ('vip' == $user->getCapability()->value) {
            return "<p>Vous n'êtes pas limité pour la création de projets.</p>";
        }

        if ('premium' == $user->getCapability()->value) {
            return sprintf('Vous êtes limité à %s projets.', $user->getCapability()->getProjectLimit());
        }

        if ('visitor' == $user->getCapability()->value) {
            return sprintf('Vous êtes limité à %s projets.', $user->getCapability()->getProjectLimit());
        }

        return '';
    }

    public function doHadCapability(User $user): bool
    {
        if ('vip' == $user->getCapability()->value) {
            return true;
        }

        return $user->getCapability()->getProjectLimit() > count($user->getProjects()) ? true : false;
    }

    public function doIconCapability(Capability $capability): string
    {
        return $capability->getIcon();
    }
}
