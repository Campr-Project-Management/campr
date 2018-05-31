<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TodoCategory;
use AppBundle\Form\TodoCategory\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TodoCategories admin controller.
 *
 * @Route("/admin/todo-category")
 */
class TodoCategoryController extends BaseController
{
    /**
     * List all TodoCategory entities.
     *
     * @Route("/list", name="app_admin_todo_category_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(TodoCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/TodoCategory:list.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Lists Todo Categories entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_todo_category_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(TodoCategory::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Show Todo Category entity.
     *
     * @Route("/{id}/show", name="app_admin_todo_category_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param TodoCategory $todoCategory
     *
     * @return Response
     */
    public function showAction(TodoCategory $todoCategory)
    {
        return $this->render(
            'AppBundle:Admin/TodoCategory:show.html.twig',
            [
                'category' => $todoCategory,
            ]
        );
    }

    /**
     * Create a Todo Category.
     *
     * @Route("/create", name="app_admin_todo_category_create")
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
                        ->trans('success.todo_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_category_list');
        }

        return $this->render(
            'AppBundle:Admin/TodoCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Todo category entity.
     *
     * @Route("/{id}/edit", name="app_admin_todo_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param TodoCategory $category
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, TodoCategory $category)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.todo_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_category_list');
        }

        return $this->render(
            'AppBundle:Admin/TodoCategory:edit.html.twig',
            [
                'id' => $category->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes  Todo Category.
     *
     * @Route("/{id}/delete", name="app_admin_todo_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param TodoCategory $category
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, TodoCategory $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
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
                    ->trans('success.todo_category.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_todo_category_list');
    }
}
