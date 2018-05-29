<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Decision;
use AppBundle\Form\Decision\CreateType as DecisionCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Decision admin controller.
 *
 * @Route("/admin/decision")
 */
class DecisionController extends BaseController
{
    /**
     * Lists all Decision entities.
     *
     * @Route("/list", name="app_admin_decision_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $decisions = $em
            ->getRepository(Decision::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Decision:list.html.twig',
            [
                'decisions' => $decisions,
            ]
        );
    }

    /**
     * Lists all Decision entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_decision_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Decision::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Decision entity.
     *
     * @Route("/create", name="app_admin_decision_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $decision = new Decision();
        $form = $this->createForm(DecisionCreateType::class, $decision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($decision);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.decision.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_list');
        }

        return $this->render(
            'AppBundle:Admin/Decision:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Decision entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_decision_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param Decision $decision
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Decision $decision)
    {
        $form = $this->createForm(DecisionCreateType::class, $decision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decision->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($decision);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.decision.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_list');
        }

        return $this->render(
            'AppBundle:Admin/Decision:edit.html.twig',
            [
                'decision' => $decision,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Decision entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_decision_show")
     * @Method({"GET"})
     *
     * @param Decision $decision
     *
     * @return Response
     */
    public function showAction(Decision $decision)
    {
        return $this->render(
            'AppBundle:Admin/Decision:show.html.twig',
            [
                'decision' => $decision,
            ]
        );
    }

    /**
     * Deletes a Decision entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_decision_delete")
     * @Method({"GET"})
     *
     * @param Request  $request
     * @param Decision $decision
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Decision $decision)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($decision);
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
                    ->trans('success.decision.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_decision_list');
    }
}
