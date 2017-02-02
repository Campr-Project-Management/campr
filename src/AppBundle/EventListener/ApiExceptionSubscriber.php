<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), '/api') !== 0) {
            return;
        }

        /** @var HttpExceptionInterface $e */
        $e = $event->getException();

        $data = [
            'messages' => isset(Response::$statusTexts[$e->getStatusCode()])
                ? Response::$statusTexts[$e->getStatusCode()]
                : 'Unknown status code',
        ];
        $response = new JsonResponse(
            $data,
            $e->getStatusCode()
        );

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
