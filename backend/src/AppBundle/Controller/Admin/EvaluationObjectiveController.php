<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\EvaluationObjective;
use AppBundle\Form\EvaluationObjective\AdminType as EvaluationObjectiveType;
use Symfony\Component\HttpFoundation\Response;

/**
 * EvaluationObjective admin controller.
 *
 * @Route("/admin/evaluation-objective")
 */
class EvaluationObjectiveController extends BaseController
{
    /**
     * Lists all Evaluation Objectives entities.
     *
     * @Route("/list", name="app_admin_evaluation_objective_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evaluationObjectives = $em
            ->getRepository(EvaluationObjective::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/EvaluationObjective:list.html.twig',
            [
                'evaluationObjectives' => $evaluationObjectives,
            ]
        );
    }

    /**
     * Lists all EvaluationObjectives entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_evaluation_objective_filtered")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(EvaluationObjective::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new EvaluationObjective entity.
     *
     * @Route("/create", name="app_admin_evaluation_objective_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $evaluationObjective = new EvaluationObjective();
        $form = $this->createForm(EvaluationObjectiveType::class, $evaluationObjective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evaluationObjective);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.evaluation_objective.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_evaluation_objective_list');
        }

        return $this->render(
            'AppBundle:Admin/EvaluationObjective:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Evaluation Objective entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_evaluation_objective_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request             $request
     * @param EvaluationObjective $evaluationObjective
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, EvaluationObjective $evaluationObjective)
    {
        $form = $this->createForm(EvaluationObjectiveType::class, $evaluationObjective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evaluationObjective);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.evaluation_objective.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_evaluation_objective_list');
        }

        return $this->render(
            'AppBundle:Admin/EvaluationObjective:edit.html.twig',
            [
                'evaluationObjective' => $evaluationObjective,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a EvaluationObjective entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_evaluation_objective_show")
     * @Method({"GET"})
     *
     * @param EvaluationObjective $evaluationObjective
     *
     * @return Response
     */
    public function showAction(EvaluationObjective $evaluationObjective)
    {
        return $this->render(
            'AppBundle:Admin/EvaluationObjective:show.html.twig',
            [
                'evaluationObjective' => $evaluationObjective,
            ]
        );
    }

    /**
     * Deletes a EvaluationObjective entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_evaluation_objective_delete")
     * @Method({"GET"})
     *
     * @param Request             $request
     * @param EvaluationObjective $evaluationObjective
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, EvaluationObjective $evaluationObjective)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($evaluationObjective);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.evaluation_objective.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_evaluation_objective_list');
    }
}
