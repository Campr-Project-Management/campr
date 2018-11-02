<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($this->isJsonRequest($request)) {
            $request->request->replace(json_decode($request->getContent(), true));
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
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

        if ('application/json' !== strtolower($temp[0])) {
            return false;
        }

        return is_array(json_decode($request->getContent(), true));
    }
}
