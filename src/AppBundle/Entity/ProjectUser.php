<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectUser.
 *
 * @ORM\Table(name="project_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectUserRepository")
 */
class ProjectUser
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var ProjectCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_category_id", referencedColumnName="id")
     * })
     */
    private $projectCategory;

    /**
     * @var ProjectRole|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_role_id", referencedColumnName="id")
     * })
     */
    private $projectRole;

    /**
     * @var ProjectDepartment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectDepartment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_department_id", referencedColumnName="id")
     * })
     */
    private $projectDepartment;

    /**
     * @var ProjectTeam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectTeam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_team_id", referencedColumnName="id")
     * })
     */
    private $projectTeam;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_in_resources", type="boolean", nullable=false, options={"default": 1})
     */
    private $showInResources = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_in_raci", type="boolean", nullable=true)
     */
    private $showInRaci;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_in_org", type="boolean", nullable=true)
     */
    private $showInOrg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    
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
     * Set showInResources.
     *
     * @param bool $showInResources
     *
     * @return ProjectUser
     */
    public function setShowInResources($showInResources)
    {
        $this->showInResources = $showInResources;

        return $this;
    }

    /**
     * Get showInResources.
     *
     * @return bool
     */
    public function getShowInResources()
    {
        return $this->showInResources;
    }

    /**
     * Set showInRaci.
     *
     * @param bool $showInRaci
     *
     * @return ProjectUser
     */
    public function setShowInRaci($showInRaci)
    {
        $this->showInRaci = $showInRaci;

        return $this;
    }

    /**
     * Get showInRaci.
     *
     * @return bool
     */
    public function getShowInRaci()
    {
        return $this->showInRaci;
    }

    /**
     * Set org.
     *
     * @param bool $showInOrg
     *
     * @return ProjectUser
     */
    public function setShowInOrg($showInOrg)
    {
        $this->showInOrg = $showInOrg;

        return $this;
    }

    /**
     * Get org.
     *
     * @return bool
     */
    public function getShowInOrg()
    {
        return $this->showInOrg;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ProjectUser
     */
    public function setCreatedAt($createdAt)
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
     * @return ProjectUser
     */
    public function setUpdatedAt($updatedAt)
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
     * Set user.
     *
     * @param User $user
     *
     * @return User
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return ProjectUser
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
     * Set projectCategory.
     *
     * @param ProjectCategory $projectCategory
     *
     * @return ProjectCategory
     */
    public function setProjectCategory(ProjectCategory $projectCategory = null)
    {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory.
     *
     * @return ProjectCategory
     */
    public function getProjectCategory()
    {
        return $this->projectCategory;
    }

    /**
     * Set projectRole.
     *
     * @param ProjectRole $projectRole
     *
     * @return ProjectUser
     */
    public function setProjectRole(ProjectRole $projectRole = null)
    {
        $this->projectRole = $projectRole;

        return $this;
    }

    /**
     * Get projectRole.
     *
     * @return ProjectRole
     */
    public function getProjectRole()
    {
        return $this->projectRole;
    }

    /**
     * Set projectDepartment.
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return ProjectUser
     */
    public function setProjectDepartment(ProjectDepartment $projectDepartment = null)
    {
        $this->projectDepartment = $projectDepartment;

        return $this;
    }

    /**
     * Get projectDepartment.
     *
     * @return ProjectDepartment
     */
    public function getProjectDepartment()
    {
        return $this->projectDepartment;
    }

    /**
     * Set projectTeam.
     *
     * @param ProjectTeam $projectTeam
     *
     * @return ProjectUser
     */
    public function setProjectTeam(ProjectTeam $projectTeam = null)
    {
        $this->projectTeam = $projectTeam;

        return $this;
    }

    /**
     * Get projectTeam.
     *
     * @return ProjectTeam
     */
    public function getProjectTeam()
    {
        return $this->projectTeam;
    }
}
