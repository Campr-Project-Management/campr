<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;
use AppBundle\Security\ProjectVoter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/workpackage")
 */
class WorkPackageController extends ApiController
{
    /**
     * All tasks for the current user.
     *
     * @Route("/list", name="app_api_workpackage_list")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $filters = $request->request->all();
        $user = $this->getUser();
        $wpQuery = $this
            ->getDoctrine()
            ->getRepository(WorkPackage::class)
            ->findUserFiltered($user, $filters)
        ;

        $pageSize = $this->getParameter('front.per_page');
        $currentPage = isset($filters['page']) ? $filters['page'] : 1;
        $paginator = new Paginator($wpQuery);
        $paginator->getQuery()
            ->setFirstResult($pageSize * ($currentPage - 1))
            ->setMaxResults($pageSize)
        ;

        $responseArray['totalItems'] = count($paginator);
        $responseArray['items'] = $paginator->getIterator()->getArrayCopy();

        return $this->createApiResponse($responseArray);
    }

    /**
     * Retrieve WorkPackage information.
     *
     * @Route("/{id}", name="app_api_workpackage_get")
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return JsonResponse
     */
    public function getAction(WorkPackage $workPackage)
    {
        return $this->createApiResponse($workPackage);
    }

    /**
     * Create a new WorkPackage.
     *
     * @Route("/create", name="app_api_workpackage_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Edit a specific WorkPackage.
     *
     * @Route("/{id}/edit", name="app_api_workpackage_edit")
     * @Method({"PATCH"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackage $wp)
    {
        if ($project = $wp->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $wp, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($wp);
            $em->flush();

            return $this->createApiResponse($wp);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific WorkPackage.
     *
     * @Route("/{id}/delete", name="app_api_workpackage_delete")
     * @Method({"GET"})
     *
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function deleteAction(WorkPackage $wp)
    {
        if ($project = $wp->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($wp);
        $em->flush();

        return $this->createApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
