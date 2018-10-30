<?php

namespace AppBundle\Controller\API\Project;

use Oneup\UploaderBundle\Controller\DropzoneController;
use Oneup\UploaderBundle\Uploader\ErrorHandler\ErrorHandlerInterface;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;
use Oneup\UploaderBundle\Uploader\Response\EmptyResponse;
use Oneup\UploaderBundle\Uploader\Storage\StorageInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/projects/{id}/uploader/media", service="app.controller.api.project.uploader.media")
 */
class MediaUploaderController extends DropzoneController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MediaUploaderController constructor.
     *
     * @param ContainerInterface    $container
     * @param StorageInterface      $storage
     * @param ErrorHandlerInterface $errorHandler
     * @param array                 $config
     * @param                       $type
     * @param LoggerInterface       $logger
     */
    public function __construct(
        ContainerInterface $container,
        StorageInterface $storage,
        ErrorHandlerInterface $errorHandler,
        array $config,
        $type,
        LoggerInterface $logger
    ) {
        parent::__construct($container, $storage, $errorHandler, $config, $type);

        $this->logger = $logger;
    }

    /**
     * @Route("/upload", name="app_api_project_uploader_media_upload", options={"expose"=true}, methods={"POST", "PUT"})
     *
     * @return JsonResponse
     */
    public function uploadAction(): JsonResponse
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
                $this->logger->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

                $translator = $this->container->get('translator');
                $message = $translator->trans('message.generic_error');
                $response = $this->createSupportedJsonResponse(
                    [
                        'messages' => [$message],
                    ]
                );
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);

                return $response;
            }
        }

        return $this->createSupportedJsonResponse($response->assemble(), $statusCode);
    }
}
