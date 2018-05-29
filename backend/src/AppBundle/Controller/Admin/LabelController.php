<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Label;
use AppBundle\Form\Label\LabelType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Label admin controller.
 *
 * @Route("/admin/label")
 */
class LabelController extends BaseController
{
    /**
     * List all label entities.
     *
     * @Route("/list", name="app_admin_label_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $labels = $this
            ->getDoctrine()
            ->getRepository(Label::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Label:list.html.twig',
            [
                'labels' => $labels,
            ]
        );
    }

    /**
     * Lists all Label entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_label_list_filtered", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Label::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Label entity.
     *
     * @Route("/{id}/show", name="app_admin_label_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Label $label
     *
     * @return Response
     */
    public function showAction(Label $label)
    {
        return $this->render(
            'AppBundle:Admin/Label:show.html.twig',
            [
                'label' => $label,
            ]
        );
    }

    /**
     * Creates a new Label entity.
     *
     * @Route("/create", name="app_admin_label_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(LabelType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.label.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_label_list');
        }

        return $this->render(
            'AppBundle:Admin/Label:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Label entity.
     *
     * @Route("/{id}/edit", name="app_admin_label_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Label   $label
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Label $label)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(LabelType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($label);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.label.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_label_list');
        }

        return $this->render(
            'AppBundle:Admin/Label:edit.html.twig',
            [
                'id' => $label->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Label entity.
     *
     * @Route("/{id}/delete", name="app_admin_label_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Label   $label
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Label $label)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
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
                    ->trans('success.label.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_label_list');
    }
}
