<?php

namespace App\Twig\Runtime;

use App\Enum\Capability;
use Twig\Extension\RuntimeExtensionInterface;

class UserCapabilityExtensionRuntime implements RuntimeExtensionInterface
{
    public function doUserCapability(Capability $capability): string
    {
        if ($capability->value == 'vip') {
            return "Vous n'êtes pas limité pour la création de projets.";
        }

        if ($capability->value == 'premium') {
            return sprintf("Vous êtes limité à %s projets.", $capability->getProjectLimit());
        }

        if ($capability->value == 'vip') {
            return sprintf("Vous êtes limité à %s projets.", $capability->getProjectLimit());
        }

        return '';
    }
}
