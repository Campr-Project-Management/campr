<?php

namespace AppBundle\EventListener;

use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();

        if (strpos($request->getPathInfo(), '/api') !== 0) {
            $exception = $event->getException();

            if ($exception instanceof UndefinedMethodException) {
                $errorCode = $exception->getCode();
                $errorMessage = $exception->getMessage();
            } elseif ($exception instanceof HttpException) {
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

        /** @var HttpExceptionInterface $e */
        $exception = $event->getException();
        $defaultMessage = 'Something went terribly wrong.';

        if ($exception instanceof UndefinedMethodException) {
            $errorCode = $exception->getCode();
            $errorMessage = $exception->getMessage();
        } elseif ($exception instanceof HttpException) {
            $errorCode = $exception->getStatusCode();
            $errorMessage = $exception->getMessage();
        } elseif ($exception instanceof InsufficientAuthenticationException) {
            $errorCode = Response::HTTP_UNAUTHORIZED;
            $errorMessage = $exception->getMessage();
        } else {
            $errorCode = 500;
            $errorMessage = $defaultMessage;
        }

        $data = [
            'messages' => [
                empty($errorMessage)
                    ? (Response::$statusTexts[$errorCode] ?? $defaultMessage)
                    : $errorMessage,
            ],
        ];

        $response = new JsonResponse(
            $data,
            $errorCode
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
