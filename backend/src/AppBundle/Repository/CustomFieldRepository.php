<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CustomFieldRepository extends EntityRepository
{
    public function findOneByFieldNameAndClass($fieldName, $class)
    {
        return $this
            ->findOneBy([
                'fieldName' => $fieldName,
                'class' => $this->getRootEntityName($class),
            ])
        ;
    }

    /**
     * Return real class from proxy objects.
     *
     * @param $className
     *
     * @return string
     */
    public function getRootEntityName($className)
    {
        return $this
            ->getEntityManager()
            ->getClassMetadata($className)
            ->rootEntityName
        ;
    }
}
