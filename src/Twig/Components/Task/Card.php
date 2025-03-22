<?php

namespace App\Twig\Components\Task;

use App\Entity\Task;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Card
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Task $task;
}
