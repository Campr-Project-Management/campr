<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $container;

    private $debug;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->debug = $container->getParameter('kernel.debug');
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();

        if (strpos($request->getPathInfo(), '/api') !== 0) {
            $exception = $event->getException();

            if ($exception instanceof HttpException) {
                $errorCode = $exception->getStatusCode();
                $errorMessage = $exception->getMessage();
            } else {
                $errorCode = 500;
                $errorMessage = 'Something went terribly wrong.';
            }

            $twig = $this->container->get('twig');
            $content = $twig->render(
                '::exception.html.twig',
                [
                    'code' => $errorCode,
                    'message' => $errorMessage,
                ]
            );

            $response = new Response($content, $errorCode);
            $event->setResponse($response);

            return;
        }

        // on api calls we want to see the errors during development
        if ($this->debug) {
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
            KernelEvents::EXCEPTION => ['onKernelException', -2],
        ];
    }
}
