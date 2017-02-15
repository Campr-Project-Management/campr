<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Portfolio;
use AppBundle\Form\Portfolio\CreateType;
use AppBundle\Security\AdminVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/portfolios")
 */
class PortfolioController extends ApiController
{
    /**
     * Get all portfolios.
     *
     * @Route(name="app_api_portfolio_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $portfolios = $this
            ->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findAll()
        ;

        return $this->createApiResponse($portfolios);
    }

    /**
     * Create a new Portfolio.
     *
     * @Route(name="app_api_portfolio_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Portfolio by id.
     *
     * @Route("/{id}", name="app_api_portfolio_get")
     * @Method({"GET"})
     *
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function getAction(Portfolio $portfolio)
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $portfolio);

        return $this->createApiResponse($portfolio);
    }

    /**
     * Edit a specific Portfolio.
     *
     * @Route("/{id}", name="app_api_portfolio_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request   $request
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Portfolio $portfolio)
    {
        $this->denyAccessUnlessGranted(AdminVoter::EDIT, $portfolio);

        $form = $this->createForm(CreateType::class, $portfolio, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            return $this->createApiResponse($portfolio, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Portfolio.
     *
     * @Route("/{id}", name="app_api_portfolio_delete")
     * @Method({"DELETE"})
     *
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function deleteAction(Portfolio $portfolio)
    {
        $this->denyAccessUnlessGranted(AdminVoter::DELETE, $portfolio);

        $em = $this->getDoctrine()->getManager();
        $em->remove($portfolio);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
