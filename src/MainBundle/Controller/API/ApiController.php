<?php

namespace MainBundle\Controller\API;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ApiController extends Controller
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

    protected function getFormErrors(Form $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $childForm) {
            if ($childForm instanceof Form) {
                $childErrors = self::getFormErrors($childForm);
                if (count($childErrors)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    protected function processForm(Request $request, FormInterface $form, $clearMissing = true)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        if (null === $data) {
            throw new BadRequestHttpException();
        }

        $form->submit($data, $clearMissing);
    }
}
