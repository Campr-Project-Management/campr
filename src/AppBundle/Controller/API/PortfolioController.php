<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Portfolio;
use AppBundle\Form\Portfolio\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/portfolio")
 */
class PortfolioController extends ApiController
{
    /**
     * Get all portfolios.
     *
     * @Route("/list", name="app_api_portfolio_list")
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
     * @Route("/create", name="app_api_portfolio_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), JsonResponse::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
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
        return $this->createApiResponse($portfolio);
    }

    /**
     * Edit a specific Portfolio.
     *
     * @Route("/{id}/edit", name="app_api_portfolio_edit")
     * @Method({"POST"})
     *
     * @param Request   $request
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Portfolio $portfolio)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $portfolio, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $portfolio->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($portfolio);
            $em->flush();

            return $this->createApiResponse($portfolio);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Portfolio.
     *
     * @Route("/{id}/delete", name="app_api_portfolio_delete")
     * @Method({"DELETE"})
     *
     * @param Portfolio $portfolio
     *
     * @return JsonResponse
     */
    public function deleteAction(Portfolio $portfolio)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($portfolio);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
