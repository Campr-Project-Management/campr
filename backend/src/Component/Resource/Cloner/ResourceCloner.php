<?php

namespace Component\Resource\Cloner;

use Component\Resource\Cloner\Annotation\Exclude;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

class ResourceCloner implements ResourceClonerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ResourceClonerRegistryInterface
     */
    private $clonerRegistry;

    /**
     * @var ResourceCloneStorageInterface
     */
    private $cloneStorage;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * ResourceCloner constructor.
     *
     * @param EntityManagerInterface          $entityManager
     * @param ResourceClonerRegistryInterface $clonerRegistry
     * @param ResourceCloneStorageInterface   $cloneStorage
     * @param Reader                          $annotationReader
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ResourceClonerRegistryInterface $clonerRegistry,
        ResourceCloneStorageInterface $cloneStorage,
        Reader $annotationReader
    ) {
        $this->entityManager = $entityManager;
        $this->clonerRegistry = $clonerRegistry;
        $this->cloneStorage = $cloneStorage;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ResourceInterface        $object
     * @param CloneScopeInterface|null $scope
     *
     * @return ResourceInterface
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface
    {
        Assert::true($this->supports($object), sprintf('Object "%s" is not supported', $this->getClass($object)));

        if ($this->cloneStorage->has($object, $object->getId())) {
            return $this->cloneStorage->get($object, $object->getId());
        }

        if (($scope && !$scope->isInScope($object)) || !$this->isCloneable($object)) {
            return $object;
        }

        $clone = $this->cloneObject($object);
        $this->resetTimestamps($clone);
        $this->cloneStorage->set($clone, $object->getId());
        $this->cloneAssociations($object, $clone);

        return $clone;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function supports(ResourceInterface $object): bool
    {
        return true;
    }

    /**
     * @param ResourceInterface $source
     *
     * @return ResourceInterface
     */
    protected function cloneObject(ResourceInterface $source): ResourceInterface
    {
        $class = $this->getClass($source);
        $target = new $class();

        foreach ($this->getFieldNames($source) as $fieldName) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $value = $accessor->getValue($source, $fieldName);

            $refClass = new \ReflectionClass($class);
            $property = $refClass->getProperty($fieldName);
            $property->setAccessible(true);

            $property->setValue($target, $value);
        }

        return $target;
    }

    /**
     * @param ResourceInterface $source
     * @param ResourceInterface $target
     */
    protected function cloneAssociations(ResourceInterface $source, ResourceInterface $target)
    {
        $associations = $this->getAssociationNames($source);
        foreach ($associations as $association) {
            if (!$this->isAssociationCloneable($source, $association)) {
                continue;
            }

            $accessor = PropertyAccess::createPropertyAccessor();
            $values = $accessor->getValue($source, $association);
            $isArray = true;
            if (!is_iterable($values)) {
                $values = [$values];
                $isArray = false;
            }

            $objects = [];
            foreach ($values as $value) {
                if (!$value) {
                    continue;
                }

                $cloner = $this->clonerRegistry->getForObject($value);
                $objects[] = $cloner->clone($value);
            }

            if (empty($objects)) {
                continue;
            }

            foreach ($objects as $object) {
                $this->entityManager->persist($object);
            }

            if ($this->isAssociationInverseSide($source, $association)) {
                continue;
            }

            if (!$isArray) {
                $objects = array_shift($objects);
            }

            $accessor->setValue($target, $association, $objects);
        }
    }

    /**
     * @param ResourceInterface $object
     *
     * @return array
     */
    protected function getAssociationNames(ResourceInterface $object): array
    {
        $metadata = $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($object));

        return $metadata->getAssociationNames();
    }

    /**
     * @param ResourceInterface $object
     * @param string            $assoc
     *
     * @return bool
     */
    protected function isAssociationInverseSide(ResourceInterface $object, string $assoc): bool
    {
        $metadata = $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($object));

        return $metadata->isAssociationInverseSide($assoc);
    }

    /**
     * @param ResourceInterface $object
     *
     * @return array
     */
    protected function getFieldNames(ResourceInterface $object): array
    {
        $metadata = $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($object));

        return array_filter(
            $metadata->getFieldNames(),
            function ($fieldName) {
                return !in_array($fieldName, ['id']);
            }
        );
    }

    /**
     * @param ResourceInterface $object
     *
     * @return string
     */
    protected function getClass(ResourceInterface $object): string
    {
        return ClassUtils::getRealClass(get_class($object));
    }

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    protected function isCloneable(ResourceInterface $object): bool
    {
        $class = new \ReflectionClass($this->getClass($object));

        return is_null($this->annotationReader->getClassAnnotation($class, Exclude::class));
    }

    /**
     * @param ResourceInterface $object
     * @param string            $association
     *
     * @return bool
     */
    protected function isAssociationCloneable(ResourceInterface $object, string $association): bool
    {
        $property = new \ReflectionProperty($this->getClass($object), $association);

        return is_null($this->annotationReader->getPropertyAnnotation($property, Exclude::class));
    }

    /**
     * @param ResourceInterface $object
     */
    protected function resetTimestamps(ResourceInterface $object)
    {
        if (!($object instanceof TimestampableInterface)) {
            return;
        }

        $object->setCreatedAt(new \DateTime());
        $object->setUpdatedAt(new \DateTime());
    }
}
