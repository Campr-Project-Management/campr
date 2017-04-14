<?php

namespace MainBundle\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class BaseController extends Controller
{
    protected function persistAndFlush($obj)
    {
        if (!is_object($obj)) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                sprintf('Only objects can be persisted and flushed.')
            );
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->persist($obj);
        $em->flush();
    }

    protected function assertClassExists(string $class)
    {
        if (empty($class) || !class_exists($class)) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                sprintf('Class "%s" does not exist.', $class)
            );
        }
    }

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
