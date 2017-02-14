<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\User;
use MainBundle\Form\User\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/user")
 */
class UserController extends ApiController
{
    /**
     * Retrieve User information.
     *
     * @Route("/{id}", name="main_api_user_get")
     * @Method({"GET"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function getAction($id = null)
    {
        $user = !$id ? $this->getUser() : $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createApiResponse($user);
    }

    /**
     * Edit current user information.
     *
     * @Route("/edit", name="main_api_user_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        if (!($user = $this->getUser())) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
            ], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(AccountType::class, $user, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->createApiResponse($user, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
