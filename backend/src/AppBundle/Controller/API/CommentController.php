<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Comment;
use AppBundle\Form\Comment\CreateType as CommentType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/comments")
 */
class CommentController extends ApiController
{
    /**
     * Edit comment.
     *
     * @Route("/{id}", name="app_api_comment_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Comment $comment
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Comment $comment)
    {
        $form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->createApiResponse($comment, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete comment.
     *
     * @Route("/{id}", name="app_api_comment_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Comment $comment
     *
     * @return JsonResponse
     */
    public function deleteAction(Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
