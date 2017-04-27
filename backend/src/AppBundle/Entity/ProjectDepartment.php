<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProjectDepartment.
 *
 * @ORM\Table(name="project_department")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectDepartmentRepository")
 */
class ProjectDepartment
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
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=255)
     */
    private $abbreviation;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $rate;

    /**
     * @var ProjectWorkCostType|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectWorkCostType")
     * @ORM\JoinColumn(name="project_work_cost_type_id")
     */
    private $projectWorkCostType;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var ProjectUser[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProjectUser", mappedBy="projectDepartments")
     */
    private $projectUsers;

    /**
     * ProjectDepartment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->projectUsers = new ArrayCollection();
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
     * @return ProjectDepartment
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
     * Set abbreviation.
     *
     * @param string $abbreviation
     *
     * @return ProjectDepartment
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return ProjectDepartment
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence.
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set rate.
     *
     * @param string $rate
     *
     * @return ProjectDepartment
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ProjectDepartment
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return ProjectDepartment
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set projectWorkCostType.
     *
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return ProjectDepartment
     */
    public function setProjectWorkCostType(ProjectWorkCostType $projectWorkCostType = null)
    {
        $this->projectWorkCostType = $projectWorkCostType;

        return $this;
    }

    /**
     * Get projectWorkCostType.
     *
     * @return ProjectWorkCostType
     */
    public function getProjectWorkCostType()
    {
        return $this->projectWorkCostType;
    }

    /**
     * Add projectUser.
     *
     * @param ProjectUser $projectUser
     *
     * @return ProjectDepartment
     */
    public function addProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;
        $projectUser->addProjectDepartment($this);

        return $this;
    }

    /**
     * Remove projectUser.
     *
     * @param ProjectUser $projectUser
     */
    public function removeProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers->removeElement($projectUser);
        $projectUser->removeProjectDepartment($this);

        return $this;
    }

    /**
     * Get projectUsers.
     *
     * @return ArrayCollection|ProjectUser[]
     */
    public function getProjectUsers()
    {
        return $this->projectUsers;
    }

    /**
     * Returns projectWorkCostType id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectWorkCostType")
     *
     * @return string
     */
    public function getProjectWorkCostTypeId()
    {
        return $this->projectWorkCostType ? $this->projectWorkCostType->getId() : null;
    }

    /**
     * Returns projectWorkCostType name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectWorkCostTypeName")
     *
     * @return string
     */
    public function getProjectWorkCostTypeName()
    {
        return $this->projectWorkCostType ? $this->projectWorkCostType->getName() : null;
    }

    /**
     * Returns department managers.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("managers")
     *
     * @return string
     */
    public function getProjectDepartmentManagers()
    {
        $managers = [];
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER)) {
                $managers[] = $projectUser;
            }
        }

        return $managers;
    }

    /**
     * Returns department members count.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("membersCount")
     *
     * @return string
     */
    public function getDepartmentMembersCount()
    {
        return $this->projectUsers->count();
    }
}
