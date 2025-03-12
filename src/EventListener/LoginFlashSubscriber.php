<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginFlashSubscriber implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var User */
        $user = $event->getAuthenticationToken()->getUser();

        /** @var SessionInterface */
        $session = $this->requestStack->getSession();
        $session->getFlashBag()->add('success', \sprintf('Bienvenue %s, vous êtes connecté !', $user->getUsername()));
    }
}
