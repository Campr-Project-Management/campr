<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AjaxResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($request->isXmlHttpRequest()) {
            // VueJS cannot interpret this range as successful so we have to do something about it
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $response->setStatusCode(200);
            } else {
                $response->headers->add([
                    'cyka' => 'blyat',
                ]);
            }
        }
    }
}
