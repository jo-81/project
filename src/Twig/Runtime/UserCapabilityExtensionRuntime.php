<?php

namespace App\Twig\Runtime;

use App\Enum\Capability;
use Twig\Extension\RuntimeExtensionInterface;

class UserCapabilityExtensionRuntime implements RuntimeExtensionInterface
{
    public function doUserCapability(Capability $capability): string
    {
        if ('vip' == $capability->value) {
            return "Vous n'êtes pas limité pour la création de projets.";
        }

        if ('premium' == $capability->value) {
            return sprintf('Vous êtes limité à %s projets.', $capability->getProjectLimit());
        }

        if ('visitor' == $capability->value) {
            return sprintf('Vous êtes limité à %s projets.', $capability->getProjectLimit());
        }

        return '';
    }

    public function doIconCapability(Capability $capability): string
    {
        return $capability->getIcon();
    }
}
