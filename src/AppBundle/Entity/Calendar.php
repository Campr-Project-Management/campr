<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * Calendar.
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CalendarRepository")
 */
class Calendar
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_based", type="boolean", nullable=false, options={"default"= 1})
     */
    private $isBased = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_baseline", type="boolean", nullable=false)
     */
    private $isBaseline;

    /**
     * @var Calendar
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Calendar")
     * @ORM\JoinColumn(name="parent_id")
     */
    private $parent;

    /**
     * @var ArrayCollection|Day[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Day", mappedBy="calendar")
     */
    private $days;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="calendar")
     */
    private $workPackages;

    /**
     * @var ArrayCollection|WorkPackageProjectWorkCostType[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackageProjectWorkCostType", mappedBy="calendar")
     */
    private $workPackageProjectWorkCostTypes;

    /**
     * @var Project|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="calendars", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    public function __construct()
    {
        $this->isBaseline = false;
        $this->days = new ArrayCollection();
        $this->workPackageProjectWorkCostTypes = new ArrayCollection();
        $this->workPackages = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Calendar
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isBased.
     *
     * @param bool $isBased
     *
     * @return Calendar
     */
    public function setIsBased($isBased)
    {
        $this->isBased = $isBased;

        return $this;
    }

    /**
     * Get isBased.
     *
     * @return bool
     */
    public function getIsBased()
    {
        return $this->isBased;
    }

    /**
     * Set isBaseline.
     *
     * @param bool $isBaseline
     *
     * @return Calendar
     */
    public function setIsBaseline($isBaseline)
    {
        $this->isBaseline = $isBaseline;

        return $this;
    }

    /**
     * Get isBaseline.
     *
     * @return bool
     */
    public function getIsBaseline()
    {
        return $this->isBaseline;
    }

    /**
     * Set parent.
     *
     * @param Calendar $parent
     *
     * @return Calendar
     */
    public function setParent(Calendar $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Calendar
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("parent")
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parent ? $this->parent->getName() : null;
    }

    /**
     * Add day.
     *
     * @param Day $day
     *
     * @return Calendar
     */
    public function addDay(Day $day)
    {
        $this->days[] = $day;

        return $this;
    }

    /**
     * Remove day.
     *
     * @param Day $day
     *
     * @return Calendar
     */
    public function removeDay(Day $day)
    {
        $this->days->removeElement($day);

        return $this;
    }

    /**
     * Get days.
     *
     * @return ArrayCollection
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return Calendar
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project ? $this->project->getName() : null;
    }

    /**
     * Add workPackageProjectWorkCostType.
     *
     * @param WorkPackageProjectWorkCostType $workPackageProjectWorkCostType
     *
     * @return Calendar
     */
    public function addWorkPackageProjectWorkCostType(WorkPackageProjectWorkCostType $workPackageProjectWorkCostType)
    {
        $this->workPackageProjectWorkCostTypes[] = $workPackageProjectWorkCostType;

        return $this;
    }

    /**
     * Remove workPackageProjectWorkCostType.
     *
     * @param WorkPackageProjectWorkCostType $workPackageProjectWorkCostType
     *
     * @return Calendar
     */
    public function removeWorkPackageProjectWorkCostType(WorkPackageProjectWorkCostType $workPackageProjectWorkCostType)
    {
        $this->workPackageProjectWorkCostTypes->removeElement($workPackageProjectWorkCostType);

        return $this;
    }

    /**
     * Get workPackageProjectWorkCostTypes.
     *
     * @return ArrayCollection
     */
    public function getWorkPackageProjectWorkCostTypes()
    {
        return $this->workPackageProjectWorkCostTypes;
    }

    /**
     * Add workPackage.
     *
     * @param WorkPackageProjectWorkCostType $workPackage
     *
     * @return Calendar
     */
    public function addWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages[] = $workPackage;

        return $this;
    }

    /**
     * Remove workPackage.
     *
     * @param WorkPackage $workPackage
     */
    public function removeWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages->removeElement($workPackage);

        return $this;
    }

    /**
     * Get workPackages.
     *
     * @return ArrayCollection
     */
    public function getWorkPackages()
    {
        return $this->workPackages;
    }
}
