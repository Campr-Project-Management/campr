<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Media;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * @Route("/{id}/download", name="app_media_download", options={"expose"=true})
     */
    public function downloadAction(Media $media)
    {
        $fs = $media->getFileSystem();
        if (!$fs || empty($fs->getConfig())) {
            throw $this->createNotFoundException();
        }

        $config = $fs->getConfig();
        $content = file_get_contents($config['path'].'/'.$media->getFileName());
        $response = new Response();
        $response->headers->set('Content-Type', $media->getMimeType());
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$media->getFileName());
        $response->setContent($content);

        return $response;
    }
}
