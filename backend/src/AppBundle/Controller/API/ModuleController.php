<?php

namespace AppBundle\Controller\API;

use AppBundle\Form\ProjectModule\Analysis\RecommendModulesType;
use Component\ProjectModule\ProjectModules;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/modules")
 */
class ModuleController extends ApiController
{
    /**
     * Get all modules.
     *
     * @Route(name="app_api_modules_list", options={"expose"=true}, methods={"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse(ProjectModules::MODULES);
    }

    /**
     * Get all recommended modules.
     *
     * @Route("/recommended", name="app_api_recommended_modules_list", options={"expose"=true}, methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function recommendedAction(Request $request): JsonResponse
    {
        $form = $this->createForm(RecommendModulesType::class, null, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $data = $form->getData();
            $modules = $this->getRecommendedModules($data);

            return $this->createApiResponse($modules, Response::HTTP_OK);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getRecommendedModules(array $data): array
    {
        return $this->get('app.analysis.resolver.project_modules')->resolve($data);
    }
}
