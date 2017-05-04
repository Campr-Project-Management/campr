<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($this->isJsonRequest($request)) {
            $request->request->replace(json_decode($request->getContent(), true));
        }
    }

    private function isJsonRequest(Request $request)
    {
        $contentType = $request->headers->get('content-type');
        if (empty($contentType)) {
            return false;
        }

        $temp = explode(';', $contentType);
        if (empty($temp[0])) {
            return false;
        }

        if (strtolower($temp[0]) !== 'application/json') {
            return false;
        }

        return is_array(json_decode($request->getContent(), true));
    }
}
