<?php

namespace MainBundle\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function createApiResponse($data, $statusCode = Response::HTTP_OK)
    {
        if (empty($data)) {
            return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
        }

        $serializedData = $this
            ->container
            ->get('jms_serializer')
            ->toArray(
                $data,
                (new SerializationContext())->setSerializeNull(true)
            )
        ;

        return new JsonResponse($serializedData, $statusCode);
    }
}
