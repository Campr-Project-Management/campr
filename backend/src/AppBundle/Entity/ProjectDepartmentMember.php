<?php

namespace AppBundle\Entity;

use Component\ProjectUser\Model\ProjectUserAwareInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="project_department_member")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectDepartmentMember")
 */
class ProjectDepartmentMember implements ResourceInterface, TimestampableInterface, ProjectUserAwareInterface
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
     * @var ProjectDepartment
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectDepartment", inversedBy="members")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_department_id", onDelete="CASCADE")
     * })
     */
    private $projectDepartment;

    /**
     * @var ProjectUser
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectUser", inversedBy="departmentMembers")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_user_id", onDelete="CASCADE")
     * })
     */
    private $projectUser;

    /**
     * @var bool
     *
     * @ORM\Column(name="`lead`", type="boolean", options={"default": false})
     */
    private $lead = false;

    /**
     * @var \DateTimeInterface|null
     *
     * @Serializer\Exclude()
     */
    protected $updatedAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Serializer\Exclude()
     */
    protected $createdAt;

    /**
     * ProjectDepartment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * @return ProjectDepartment|null
     */
    public function getProjectDepartment()
    {
        return $this->projectDepartment;
    }

    /**
     * @param ProjectDepartment $projectDepartment
     */
    public function setProjectDepartment(ProjectDepartment $projectDepartment = null)
    {
        $this->projectDepartment = $projectDepartment;
    }

    /**
     * @return ProjectUser|null
     */
    public function getProjectUser()
    {
        return $this->projectUser;
    }

    /**
     * @param ProjectUser $projectUser
     */
    public function setProjectUser(ProjectUser $projectUser = null)
    {
        $this->projectUser = $projectUser;
    }

    /**
     * @return bool
     */
    public function isLead(): bool
    {
        return $this->lead;
    }

    /**
     * @param bool $lead
     */
    public function setLead(bool $lead = false): void
    {
        $this->lead = $lead;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string|null
     */
    public function getFullName()
    {
        $projectUser = $this->getProjectUser();
        if (!$projectUser) {
            return null;
        }

        return $this->getProjectUser()->getUserFullName();
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getProjectUserId()
    {
        $projectUser = $this->getProjectUser();
        if (!$projectUser) {
            return null;
        }

        return $projectUser->getId();
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return bool
     */
    public function isDeleted()
    {
        $projectUser = $this->getProjectUser();
        if (!$projectUser) {
            return true;
        }

        return $projectUser->isUserDeleted();
    }
}
