<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Info;
use AppBundle\Entity\Project;
use Knp\Component\Pager\Pagination\SlidingPagination;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Info\ApiCreateType as InfoType;

/**
 * @Route("/api/projects/{id}")
 */
class InfoController extends ApiController
{
    /**
     * @Route("/infos", name="app_api_project_infos", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function infosAction(Request $request, Project $project)
    {
        $queryBuilder = $this
            ->getDoctrine()
            ->getRepository(Info::class)
            ->getQueryBuilderByProjectAndFilters($project, $request->query)
        ;

        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per_page', 10);

        /** @var SlidingPagination $paginator */
        $paginator = $this->get('knp_paginator')->paginate($queryBuilder, $page, $perPage);

        $out = [
            'items' => $paginator->getItems(),
            'currentPage' => (int) $paginator->getPage(),
            'numberOfPages' => $paginator->getPageCount(),
            'numberOfItems' => $paginator->getTotalItemCount(),
            'itemsPerPage' => $paginator->getItemNumberPerPage(),
        ];

        return $this->createApiResponse($out);
    }

    /**
     * @Route("/infos", name="app_api_project_create_info", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createInfoAction(Request $request, Project $project)
    {
        $info = new Info();
        $info->setProject($project);
        $form = $this->createForm(
            InfoType::class,
            $info,
            [
                'method' => Request::METHOD_POST,
                'csrf_protection' => false,
            ]
        );

        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($info);
            $em->flush();

            return $this->createApiResponse($info, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
