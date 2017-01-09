<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;
use AppBundle\Security\ProjectVoter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/workpackage")
 */
class WorkPackageController extends Controller
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
        $filters = json_decode($request->getContent(), true);
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

        $tasks = [];
        foreach ($paginator as $wp) {
            $tasks[] = $this->serialize($wp);
        }

        return new JsonResponse($tasks);
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
        return new JsonResponse($this->serialize($workPackage));
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
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return new JsonResponse($this->serialize($form->getData()));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
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

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, $wp, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($wp);
            $em->flush();

            return new JsonResponse($this->serialize($wp));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
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

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create array with needed information from WorkPackage object.
     *
     * @param WorkPackage $workPackage
     *
     * @return array
     */
    private function serialize(WorkPackage $wp)
    {
        return [
            'id' => $wp->getId(),
            'name' => $wp->getName(),
            'project' =>  $wp->getProject() ? $wp->getProject()->getId() : null,
            'project_name' => $wp->getProject() ? $wp->getProject()->getName() : null,
            'responsibility' => $wp->getResponsibility() ? $wp->getResponsibility()->getId() : null,
            'responsibility_name' => $wp->getResponsibility() ? $wp->getResponsibility()->getFullName() : null,
            'schedules' => [
                'base' => [
                    'start' => $wp->getScheduledStartAt() ? $wp->getScheduledStartAt()->format('Y-m-d H:i:s') : null,
                    'finish' => $wp->getScheduledFinishAt() ? $wp->getScheduledFinishAt()->format('Y-m-d H:i:s') : null,
                ],
                'forecast' => [
                    'start' => $wp->getForecastStartAt() ? $wp->getScheduledStartAt()->format('Y-m-d H:i:s') : null,
                    'finish' => $wp->getForecastFinishAt() ? $wp->getScheduledStartAt()->format('Y-m-d H:i:s') : null,
                ],
            ],
            'progress' => $wp->getProgress(),
            'content' => $wp->getContent(),
            'color_status' => [
                'id' => $wp->getColorStatus() ? $wp->getColorStatus()->getId() : null,
                'name' => $wp->getColorStatus() ? $wp->getColorStatus()->getName() : null,
                'color' => $wp->getColorStatus() ? $wp->getColorStatus()->getColor() : null,
            ],
        ];
    }
}
