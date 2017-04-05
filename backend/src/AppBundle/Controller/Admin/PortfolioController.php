<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Portfolio;
use AppBundle\Form\Portfolio\CreateType as PortfolioCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Portfolio admin controller.
 *
 * @Route("/admin/portfolio")
 */
class PortfolioController extends BaseController
{
    /**
     * Lists all Portfolio entities.
     *
     * @Route("/list", name="app_admin_portfolio_list")
     * @Method("GET")
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $portfolios = $em
            ->getRepository(Portfolio::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Portfolio:list.html.twig',
            [
                'portfolios' => $portfolios,
            ]
        );
    }

    /**
     * Lists all Portfolio entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_portfolio_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Portfolio::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Portfolio entity.
     *
     * @Route("/create", name="app_admin_portfolio_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioCreateType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.portfolio.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_portfolio_list');
        }

        return $this->render(
            'AppBundle:Admin/Portfolio:create.html.twig',
            [
                'portfolio' => $portfolio,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Portfolio entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_portfolio_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Portfolio $portfolio
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Portfolio $portfolio)
    {
        $form = $this->createForm(PortfolioCreateType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.portfolio.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_portfolio_list');
        }

        return $this->render(
            'AppBundle:Admin/Portfolio:edit.html.twig',
            [
                'portfolio' => $portfolio,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Portfolio entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_portfolio_show")
     * @Method({"GET"})
     *
     * @param Portfolio $portfolio
     *
     * @return Response
     */
    public function showAction(Portfolio $portfolio)
    {
        return $this->render(
            'AppBundle:Admin/Portfolio:show.html.twig',
            [
                'portfolio' => $portfolio,
            ]
        );
    }

    /**
     * Deletes a Portfolio entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_portfolio_delete")
     * @Method({"GET"})
     *
     * @param Request   $request
     * @param Portfolio $portfolio
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Portfolio $portfolio)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($portfolio);
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
                    ->trans('success.portfolio.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_portfolio_list');
    }
}
