<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
	
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $message['code'] = $exception->getCode();
        $message['message'] = $exception->getMessage();
        $response = new Response();
        $response->setContent(json_encode($message));
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $event->setResponse($response);
    }
}
