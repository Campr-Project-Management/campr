<?php

namespace AppBundle\Controller\API\Project;

use Oneup\UploaderBundle\Controller\DropzoneController;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;
use Oneup\UploaderBundle\Uploader\Response\EmptyResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/projects/{id}/uploader/media", service="app.controller.api.project.uploader.media")
 */
class MediaUploaderController extends DropzoneController
{
    /**
     * @Route("/upload", name="app_api_project_uploader_media_upload", options={"expose"=true}, methods={"POST", "PUT"})
     *
     * @return JsonResponse
     */
    public function upload()
    {
        $request = $this->getRequest();
        $response = new EmptyResponse();
        $files = $this->getFiles($request->files);
        $statusCode = 200;

        $chunked = null !== $request->request->get('dzchunkindex');

        foreach ($files as $file) {
            try {
                $chunked ?
                    $this->handleChunkedUpload($file, $response, $request) :
                    $this->handleUpload($file, $response, $request);
            } catch (ValidationException $e) {
                $translator = $this->container->get('translator');
                $message = $translator->trans($e->getMessage());
                $response = $this->createSupportedJsonResponse(
                    [
                        'messages' => [$message],
                    ]
                );
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);

                return $response;
            } catch (\Exception $e) {
                $translator = $this->container->get('translator');
                $message = $translator->trans($e->getMessage());
                $response = $this->createSupportedJsonResponse(
                    [
                        'messages' => [$message],
                    ]
                );
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

                return $response;
            }
        }

        return $this->createSupportedJsonResponse($response->assemble(), $statusCode);
    }
}
