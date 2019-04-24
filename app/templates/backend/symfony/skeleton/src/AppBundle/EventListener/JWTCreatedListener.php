<?php

namespace AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $user = $event->getUser();
        $payload['id'] = $user->getId();
        $payload['roles'] = $user->getRoles();
        $event->setData($payload);
    }
}
