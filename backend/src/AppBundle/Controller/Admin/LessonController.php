<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Lesson;
use AppBundle\Form\Lesson\AdminType as LessonType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lesson admin controller.
 *
 * @Route("/admin/lesson")
 */
class LessonController extends BaseController
{
    /**
     * Lists all Lesson entities.
     *
     * @Route("/list", name="app_admin_lesson_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em
            ->getRepository(Lesson::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Lesson:list.html.twig',
            [
                'lessons' => $lessons,
            ]
        );
    }

    /**
     * Lists all Lesson entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_lesson_filtered")
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
        $response = $dataTableService->paginateByColumn(Lesson::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Lesson entity.
     *
     * @Route("/create", name="app_admin_lesson_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.lesson.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_lesson_list');
        }

        return $this->render(
            'AppBundle:Admin/Lesson:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Lesson entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_lesson_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Lesson  $lesson
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Lesson $lesson)
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.lesson.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_lesson_list');
        }

        return $this->render(
            'AppBundle:Admin/Lesson:edit.html.twig',
            [
                'lesson' => $lesson,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Lesson entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_lesson_show")
     * @Method({"GET"})
     *
     * @param Lesson $lesson
     *
     * @return Response
     */
    public function showAction(Lesson $lesson)
    {
        return $this->render(
            'AppBundle:Admin/Lesson:show.html.twig',
            [
                'lesson' => $lesson,
            ]
        );
    }

    /**
     * Deletes a Lesson entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_lesson_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Lesson  $lesson
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Lesson $lesson)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($lesson);
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
                    ->trans('success.lesson.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_lesson_list');
    }
}
