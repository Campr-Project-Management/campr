<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Media;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/api/media")
 */
class MediaController extends ApiController
{
    /**
     * Download media file.
     *
     * @Route("/{id}/download", name="app_api_media_download", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Media $media
     *
     * @return Response
     */
    public function downloadAction(Media $media)
    {
        $fs = $this
            ->get('app.service.filesystem')
            ->createFileSystem($media->getFileSystem())
        ;

        $response = new Response($fs->read($media->getPath()));

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $media->getOriginalName()
        );

        $response->headers->set('Content-Type', $media->getMimeType());
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
