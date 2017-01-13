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
    public function getAction($id)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id)
        ;

        if (!$user) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('api.general.not_found', [], 'api_responses'),
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
        if ((!$user = $this->getUser())) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('api.general.not_found', [], 'api_responses'),
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->all();
        $form = $this->createForm(AccountType::class, $user, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->createApiResponse($user);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }
}
