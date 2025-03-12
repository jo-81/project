<?php

namespace App\DataFixtures\Providers;

use App\Enum\Priority;
use App\Enum\Capability;

class EnumerationProvider
{
    public function getCapability(string $capability): Capability
    {
        return Capability::from($capability);
    }

    public function getPriority(string $priority): Priority
    {
        return Priority::from($priority);
    }
}
