<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WorkPackage.
 *
 * @ORM\Table(name="work_package_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkPackageCategoryRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 */
class WorkPackageCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="workPackageCategory")
     */
    private $workPackages;

    /**
     * WorkPackageCategory constructor.
     */
    public function __construct()
    {
        $this->workPackages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return WorkPackage[]|ArrayCollection
     */
    public function getWorkPackages()
    {
        return $this->workPackages;
    }

    /**
     * Add WorkPackages.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageCategory
     */
    public function addWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages[] = $workPackage;

        return $this;
    }

    /**
     * Remove WorkPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageCategory
     */
    public function removeWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages->removeElement($workPackage);

        return $this;
    }
}
