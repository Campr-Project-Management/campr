<?php

namespace MainBundle\EventListener;

use AppBundle\Entity\Measure;
use AppBundle\Entity\MeasureComment;
use AppBundle\Entity\Opportunity;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Risk;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\User;
use AppBundle\Entity\Team;
use AppBundle\Entity\WorkPackage;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Storage\StorageInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

/**
 * Class ImageSerializeListener
 * Creates image url for the serializer.
 */
class ImageSerializeListener
{
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * ImageSerializeListener constructor.
     *
     * @param StorageInterface $storage
     * @param RequestStack     $requestStack
     */
    public function __construct(StorageInterface $storage, RequestStack $requestStack)
    {
        $this->storage = $storage;
        $this->requestStack = $requestStack;
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        $visitor = $event->getVisitor();

        switch (true) {
            case $object instanceof User:
                $visitor->addData('avatar', $this->getUri($object, 'avatarFile'));
                break;
            case $object instanceof Team:
            case $object instanceof Project:
                $visitor->addData('logo', $this->getUri($object, 'logoFile'));
                break;
            case $object instanceof ProjectUser:
            case $object instanceof SubteamMember:
                if ($object->getUser() instanceof User) {
                    if ($object->getUser()->getAvatar()) {
                        $visitor->addData('userAvatar', $this->getUri($object->getUser(), 'avatarFile'));
                    } else {
                        $visitor->addData('userAvatar', $object->getUser()->getGravatar());
                    }
                }
                break;
            case $object instanceof WorkPackage:
            case $object instanceof Opportunity:
            case $object instanceof Risk:
            case $object instanceof Measure:
            case $object instanceof MeasureComment:
                if ($object->getResponsibility() instanceof User) {
                    if ($object->getResponsibility()->getAvatar()) {
                        $visitor->addData('responsibilityAvatar', $this->getUri($object->getResponsibility(), 'avatarFile'));
                    } else {
                        $visitor->addData('responsibilityAvatar', $object->getResponsibility()->getGravatar());
                    }
                }
                if (method_exists($object, 'getCreatedBy') && $object->getCreatedBy() instanceof User) {
                    if ($object->getCreatedBy()->getAvatar()) {
                        $visitor->addData('createdByAvatar', $this->getUri($object->getCreatedBy(), 'avatarFile'));
                    } else {
                        $visitor->addData('createdByAvatar', $object->getCreatedBy()->getGravatar());
                    }
                }
                break;
        }
    }

    /**
     * Gets the full uri for an image.
     *
     * @param $object
     * @param $fieldName
     *
     * @return null|string
     */
    private function getUri($object, $fieldName)
    {
        $uri = $this->storage->resolveUri($object, $fieldName);
        $request = $this->requestStack->getCurrentRequest();

        return $uri ? $request->getUriForPath($uri) : null;
    }
}
