<?php

namespace MainBundle\Controller;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BaseController extends Controller
{
    protected function persistAndFlush($obj)
    {
        if (!is_object($obj)) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Only objects can be persisted and flushed.'
            );
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->persist($obj);
        $em->flush();
    }

    protected function refreshEntity($obj)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->refresh($obj);
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

    protected function createApiResponse($data, $statusCode = Response::HTTP_OK, $emptyData = '')
    {
        if (empty($data)) {
            if ('' === $emptyData && is_array($data)) {
                $emptyData = [];
            }

            return new JsonResponse($emptyData, JsonResponse::HTTP_NO_CONTENT);
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

    protected function createdTranslatedAccessDeniedException(string $message)
    {
        throw $this->createAccessDeniedException(
            $this
                ->get('translator')
                ->trans($message)
        );
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->get('event_dispatcher');
    }

    /**
     * @param string $name
     * @param Event  $event
     *
     * @return Event
     */
    protected function dispatchEvent(string $name, Event $event): Event
    {
        return $this->getEventDispatcher()->dispatch($name, $event);
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public function merge_multidimensional_arrays(array &$array1, array &$array2)
    {
        $data = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($data[$key]) && is_array($data[$key])) {
                $data[$key] = $this->merge_multidimensional_arrays($data[$key], $value);
            } elseif (is_numeric($key)) {
                if (!in_array($value, $data)) {
                    $data[] = $value;
                }
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
