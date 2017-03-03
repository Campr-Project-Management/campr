<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $headers = $request->headers->all();

        if (isset($headers['content-type'])
            && (in_array('application/json;charset=UTF-8', $headers['content-type'])
                || in_array('application/json', $headers['content-type']))
        ) {
            if ($content = $request->getContent()) {
                $request->request->replace(json_decode($content, true));
            }
        }
    }
}
