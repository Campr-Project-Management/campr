<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Day;
use AppBundle\Form\Day\CreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Day admin controller.
 *
 * @Route("/admin/day")
 */
class DayController extends BaseController
{
    /**
     * Lists all Day entities.
     *
     * @Route("/list", name="app_admin_day_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $days = $em
            ->getRepository(Day::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Day:list.html.twig',
            [
                'days' => $days,
            ]
        );
    }

    /**
     * Lists all Day entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_day_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Day::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Day entity.
     *
     * @Route("/{id}/show", name="app_admin_day_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Day $day
     *
     * @return Response
     */
    public function showAction(Day $day)
    {
        return $this->render(
            'AppBundle:Admin/Day:show.html.twig',
            [
                'day' => $day,
            ]
        );
    }

    /**
     * Creates a new Day entity.
     *
     * @Route("/create", name="app_admin_day_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class);
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
                        ->trans('success.day.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_day_list');
        }

        return $this->render(
            'AppBundle:Admin/Day:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_day_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Day     $day
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Day $day)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($day);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.day.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_day_list');
        }

        return $this->render(
            'AppBundle:Admin/Day:edit.html.twig',
            [
                'id' => $day->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_day_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Day     $day
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Day $day)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($day);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.day.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.day.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.day.delete.generic', [], 'flashes')
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

        return $this->redirectToRoute('app_admin_day_list');
    }
}
