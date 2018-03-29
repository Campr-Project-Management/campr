<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AjaxResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($request->isXmlHttpRequest()) {
            // VueJS cannot interpret this range as successful so we have to do something about it
            $range2xx = $response->getStatusCode() > Response::HTTP_OK && $response->getStatusCode() < Response::HTTP_MULTIPLE_CHOICES;
            $miscAllowed = [
                Response::HTTP_NO_CONTENT,
                Response::HTTP_BAD_REQUEST,
                Response::HTTP_UNAUTHORIZED,
                Response::HTTP_FORBIDDEN,
            ];
            if ($range2xx || in_array($response->getStatusCode(), $miscAllowed, true)) {
                if ($response instanceof JsonResponse) {
                    $content = $response->getContent();
                    // @todo the third condition is just a temporary solution, it will be replaced later
                    if (!empty($content) && !in_array($content, ['""', "''"], true) && strpos($content, 'messages') !== false) {
                        $content = json_decode($content, true);
                        $content['error'] = true;
                        $response->setData($content);
                    }
                }
                $response->setStatusCode(Response::HTTP_OK);
            }
        }
    }
}
