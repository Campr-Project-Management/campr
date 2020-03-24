<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * Label.
 *
 * @ORM\Table(name="label")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LabelRepository")
 */
class Label implements ResourceInterface, CloneableInterface
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7)
     */
    private $color;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="labels")
     */
    private $workPackages;

    public function __construct()
    {
        $this->workPackages = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->title;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Add workpackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return Label
     */
    public function addWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages[] = $workPackage;

        return $this;
    }

    /**
     * Remove workpackage.
     *
     * @param WorkPackage $workPackage
     */
    public function removeWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages->removeElement($workPackage);
    }

    /**
     * Get workpackages.
     *
     * @return ArrayCollection|WorkPackage[]
     */
    public function getWorkPackages()
    {
        return $this->workPackages;
    }

    /**
     * Returns number of workpackages.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("openWorkPackagesNumber")
     *
     * @return string
     */
    public function getOpenWorkPackagesNumber()
    {
        $count = 0;
        foreach ($this->workPackages as $wp) {
            if ($wp->getWorkPackageStatusId() && WorkPackageStatus::CLOSED !== $wp->getWorkPackageStatusId()) {
                ++$count;
            }
        }

        return $count;
    }
}
