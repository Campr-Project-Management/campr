<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Assignment.
 *
 * @ORM\Table(name="assignment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AssignmentRepository")
 */
class Assignment implements ResourceInterface, CloneableInterface, TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="external_id", type="integer", unique=true, nullable=true)
     */
    private $externalId;

    /**
     * @var ArrayCollection|Timephase[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Timephase", mappedBy="assignment")
     */
    private $timephases;

    /**
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage", inversedBy="assignments")
     * @ORM\JoinColumn(name="work_package_id", onDelete="CASCADE")
     */
    private $workPackage;

    /**
     * @var WorkPackageProjectWorkCostType
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackageProjectWorkCostType", inversedBy="assignments")
     * @ORM\JoinColumn(name="work_package_project_work_cost_type_id", onDelete="CASCADE")
     */
    private $workPackageProjectWorkCostType;

    /**
     * @var int
     *
     * @ORM\Column(name="percent_work_complete", type="integer", nullable=false, options={"default"=0})
     */
    private $percentWorkComplete = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="milestone", type="integer", nullable=false)
     */
    private $milestone;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false, options={"default"=0})
     */
    private $confirmed = false;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     * @Gedmo\Timestampable(on="update")

     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * Assignment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->timephases = new ArrayCollection();
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
     * Set percentWorkComplete.
     *
     * @param int $percentWorkComplete
     *
     * @return Assignment
     */
    public function setPercentWorkComplete($percentWorkComplete)
    {
        $this->percentWorkComplete = $percentWorkComplete;

        return $this;
    }

    /**
     * Get percentWorkComplete.
     *
     * @return int
     */
    public function getPercentWorkComplete()
    {
        return $this->percentWorkComplete;
    }

    /**
     * Set milestone.
     *
     * @param int $milestone
     *
     * @return Assignment
     */
    public function setMilestone($milestone)
    {
        $this->milestone = $milestone;

        return $this;
    }

    /**
     * Get milestone.
     *
     * @return int
     */
    public function getMilestone()
    {
        return $this->milestone;
    }

    /**
     * Set confirmed.
     *
     * @param bool $confirmed
     *
     * @return Assignment
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed.
     *
     * @return bool
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set startedAt.
     *
     * @param \DateTime $startedAt
     *
     * @return Assignment
     */
    public function setStartedAt(\DateTime $startedAt = null)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt.
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set finishedAt.
     *
     * @param \DateTime $finishedAt
     *
     * @return Assignment
     */
    public function setFinishedAt(\DateTime $finishedAt = null)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt.
     *
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Add timephase.
     *
     * @param Timephase $timephase
     *
     * @return Assignment
     */
    public function addTimephase(Timephase $timephase)
    {
        $this->timephases[] = $timephase;

        return $this;
    }

    /**
     * Remove timephase.
     *
     * @param Timephase $timephase
     */
    public function removeTimephase(Timephase $timephase)
    {
        $this->timephases->removeElement($timephase);
    }

    /**
     * Get timephases.
     *
     * @return ArrayCollection
     */
    public function getTimephases()
    {
        return $this->timephases;
    }

    /**
     * Set workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return Assignment
     */
    public function setWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackage = $workPackage;

        return $this;
    }

    /**
     * Get workPackage.
     *
     * @return WorkPackage
     */
    public function getWorkPackage()
    {
        return $this->workPackage;
    }

    /**
     * Returns the WorkPackage id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackage")
     *
     * @return string
     */
    public function getWorkPackageId()
    {
        return $this->workPackage ? $this->workPackage->getId() : null;
    }

    /**
     * Returns the WorkPackage name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageName")
     *
     * @return string
     */
    public function getWorkPackageName()
    {
        return $this->workPackage ? $this->workPackage->getName() : null;
    }

    /**
     * Get percentWorkComplete.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("percentWorkComplete")
     *
     * @return string
     */
    public function getPercentWork()
    {
        return $this->percentWorkComplete;
    }

    /**
     * Set workPackageProjectWorkCostType.
     *
     * @param WorkPackageProjectWorkCostType $workPackageProjectWorkCostType
     *
     * @return Assignment
     */
    public function setWorkPackageProjectWorkCostType(WorkPackageProjectWorkCostType $workPackageProjectWorkCostType)
    {
        $this->workPackageProjectWorkCostType = $workPackageProjectWorkCostType;

        return $this;
    }

    /**
     * Get workPackageProjectWorkCostType.
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function getWorkPackageProjectWorkCostType()
    {
        return $this->workPackageProjectWorkCostType;
    }

    /**
     * Returns workPackageProjectWorkCostType id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageProjectWorkCostType")
     *
     * @return string
     */
    public function getWorkPackageProjectWorkCostTypeId()
    {
        return $this->workPackageProjectWorkCostType ? $this->workPackageProjectWorkCostType->getId() : null;
    }

    /**
     * Returns workPackageProjectWorkCostType name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageProjectWorkCostTypeName")
     *
     * @return string
     */
    public function getWorkPackageProjectWorkCostTypeName()
    {
        return $this->workPackageProjectWorkCostType ? $this->workPackageProjectWorkCostType->getName() : null;
    }

    /**
     * Set externalId.
     *
     * @param int|null $externalId
     *
     * @return Assignment
     */
    public function setExternalId($externalId = null)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId.
     *
     * @return int|null
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
}
