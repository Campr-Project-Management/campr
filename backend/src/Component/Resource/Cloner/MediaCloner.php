<?php

namespace Component\Resource\Cloner;

use AppBundle\Entity\Media;
use Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class MediaCloner.
 */
class MediaCloner implements ResourceClonerInterface
{
    /**
     * @var ResourceClonerInterface
     */
    private $resourceCloner;

    /**
     * MediaCloner constructor.
     *
     * @param ResourceClonerInterface $resourceCloner
     */
    public function __construct(ResourceClonerInterface $resourceCloner)
    {
        $this->resourceCloner = $resourceCloner;
    }

    /**
     * @param ResourceInterface|Media  $object
     * @param CloneScopeInterface|null $scope
     *
     * @return Media
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface
    {
        try {
            $file = new File($object->getRealPath());

            /** @var Media $clone */
            $clone = $this->resourceCloner->clone($object);
            $clone->setFile($file);
        } catch (FileNotFoundException $e) {
            return $object;
        }

        return $clone;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function supports(ResourceInterface $object): bool
    {
        return $object instanceof Media;
    }
}
