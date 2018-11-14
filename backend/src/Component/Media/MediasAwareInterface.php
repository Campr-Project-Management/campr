<?php

namespace Component\Media;

use AppBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;

interface MediasAwareInterface
{
    /**
     * Get medias.
     *
     * @return ArrayCollection|Media[]
     */
    public function getMedias();

    /**
     * @param Media $media
     *
     * @return $this
     */
    public function removeMedia(Media $media);

    /**
     * Add media.
     *
     * @param Media $media
     */
    public function addMedia(Media $media);

    /**
     * @param Media[]|ArrayCollection $medias
     */
    public function setMedias($medias);
}
