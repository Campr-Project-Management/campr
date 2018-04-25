<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * WorkPackage admin controller.
 *
 * @Route("/admin/workpackage")
 */
class WorkPackageController extends BaseController
{
    /**
     * List all WorkPackage entities.
     *
     * @Route("/list", name="app_admin_workpackage_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $workPackages = $this
            ->getDoctrine()
            ->getRepository(WorkPackage::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkPackage:list.html.twig',
            [
                'workPackages' => $workPackages,
            ]
        );
    }

    /**
     * Lists all WorkPackage entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_workpackage_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(WorkPackage::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays WorkPackage entity.
     *
     * @Route("/{id}/show", name="app_admin_workpackage_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return Response
     */
    public function showAction(WorkPackage $workPackage)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackage:show.html.twig',
            [
                'workPackage' => $workPackage,
            ]
        );
    }

    /**
     * Create a new WorkPackage entity.
     *
     * @Route("/create", name="app_admin_workpackage_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(
            CreateType::class,
            new WorkPackage(),
            [
                'validation_groups' => ['Default', 'create'],
            ]
        );

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView(
                'AppBundle:Admin/WorkPackage/Partials:form.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new JsonResponse($html);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workpackage.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackage:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing WorkPackage entity.
     *
     * @Route("/{id}/edit", name="app_admin_workpackage_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param WorkPackage $workPackage
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, WorkPackage $workPackage)
    {
        $form = $this->createForm(
            CreateType::class,
            $workPackage,
            ['validation_groups' => ['Default', 'edit']]
        );

        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView(
                'AppBundle:Admin/WorkPackage/Partials:form_edit.html.twig',
                [
                    'id' => $workPackage->getId(),
                    'form' => $form->createView(),
                ]
            );

            return new JsonResponse($html);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workPackage);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workpackage.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackage:edit.html.twig',
            [
                'id' => $workPackage->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific WorkPackage entity.
     *
     * @Route("/{id}/delete", name="app_admin_workpackage_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param WorkPackage $workPackage
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, WorkPackage $workPackage)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workPackage);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.workpackage.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.workpackage.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.workpackage.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                $flashKey,
                $flashMessage
            )
        ;

        return $this->redirectToRoute('app_admin_workpackage_list');
    }
}
