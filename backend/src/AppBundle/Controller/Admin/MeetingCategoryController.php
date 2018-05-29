<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MeetingCategory;
use AppBundle\Form\MeetingCategory\BaseType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * MeetingCategory admin controller.
 *
 * @Route("/admin/meeting-category")
 */
class MeetingCategoryController extends BaseController
{
    /**
     * List all Meeting Category entities.
     *
     * @Route("/list", name="app_admin_meeting_category_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $meetingCategories = $this
            ->getDoctrine()
            ->getRepository(MeetingCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/MeetingCategory:list.html.twig',
            [
                'meetingCategories' => $meetingCategories,
            ]
        );
    }

    /**
     * Lists all Meeting Category entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_meeting_category_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(MeetingCategory::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays MeetingCategory entity.
     *
     * @Route("/{id}/show", name="app_admin_meeting_category_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingCategory $meetingCategory
     *
     * @return Response
     */
    public function showAction(MeetingCategory $meetingCategory)
    {
        return $this->render(
            'AppBundle:Admin/MeetingCategory:show.html.twig',
            [
                'meetingCategory' => $meetingCategory,
            ]
        );
    }

    /**
     * Creates a new Meeting Category entity.
     *
     * @Route("/create", name="app_admin_meeting_category_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(BaseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($form->getData());
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.meeting_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_category_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing MeetingCategory entity.
     *
     * @Route("/{id}/edit", name="app_admin_meeting_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request         $request
     * @param MeetingCategory $meetingCategory
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, MeetingCategory $meetingCategory)
    {
        $form = $this->createForm(BaseType::class, $meetingCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($meetingCategory);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.meeting_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_category_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingCategory:edit.html.twig',
            [
                'id' => $meetingCategory->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Meeting Category entity.
     *
     * @Route("/{id}/delete", name="app_admin_meeting_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request         $request
     * @param MeetingCategory $meetingCategory
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, MeetingCategory $meetingCategory)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meetingCategory);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.meeting_category.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.meeting_category.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.meeting_category.delete.generic', [], 'flashes')
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

        return $this->redirectToRoute('app_admin_meeting_category_list');
    }
}
