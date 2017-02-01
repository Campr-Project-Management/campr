<?php

namespace MainBundle\Controller\API;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends Controller
{
    protected function createApiResponse($data, $statusCode = Response::HTTP_OK)
    {
        $serializedData = null;
        if ($data) {
            $serializedData = $this
                ->container
                ->get('jms_serializer')
                ->toArray(
                    $data,
                    (new SerializationContext())->setSerializeNull(true)
                 )
            ;
        }

        return new JsonResponse($serializedData, $statusCode);
    }
}
