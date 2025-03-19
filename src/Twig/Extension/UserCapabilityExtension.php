<?php

namespace App\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use App\Twig\Runtime\UserCapabilityExtensionRuntime;

class UserCapabilityExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'user_capability', [UserCapabilityExtensionRuntime::class, 'doUserCapability'], ['is_safe' => ['html']]
            ),
            new TwigFunction('icon_capability', [UserCapabilityExtensionRuntime::class, 'doIconCapability']),
            new TwigFunction('had_capability', [UserCapabilityExtensionRuntime::class, 'doHadCapability']),
        ];
    }
}
