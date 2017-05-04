<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="projectUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Project
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="projectUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var ProjectCategory
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_category_id", referencedColumnName="id")
     * })
     */
    private $projectCategory;

    /**
     * @var ProjectRole[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProjectRole", inversedBy="projectUsers", cascade={"all"})
     * @ORM\JoinTable(
     *     name="project_user_project_role",
     *     joinColumns={
     *         @ORM\JoinColumn(name="project_user_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="project_role_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $projectRoles;

    /**
     * @var ProjectDepartment[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProjectDepartment", inversedBy="projectUsers", cascade={"all"})
     * @ORM\JoinTable(
     *     name="project_user_project_department",
     *     joinColumns={
     *         @ORM\JoinColumn(name="project_user_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="project_department_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $projectDepartments;

    /**
     * @var ProjectTeam
     *
     * @Serializer\Exclude()
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
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=128, nullable=true)
     */
    private $company;

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
     * ProjectUser constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->projectRoles = new ArrayCollection();
        $this->projectDepartments = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getUser()->getFullName();
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
     * @return ProjectUser
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
     * Set user.
     *
     * @param User $user
     *
     * @return ProjectUser
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
     * @return ProjectUser
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

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return ProjectUser
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Returns user id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user ? $this->user->getId() : null;
    }

    /**
     * Returns user full name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFullName")
     *
     * @return string
     */
    public function getUserFullName()
    {
        return $this->user ? $this->user->getFullName() : null;
    }

    /**
     * Returns user facebook.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFacebook")
     *
     * @return string
     */
    public function getUserFacebook()
    {
        return $this->user ? $this->user->getFacebook() : null;
    }

    /**
     * Returns user twitter.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userTwitter")
     *
     * @return string
     */
    public function getUserTwitter()
    {
        return $this->user ? $this->user->getTwitter() : null;
    }

    /**
     * Returns user linkedin.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userLinkedIn")
     *
     * @return string
     */
    public function getUserLinkedIn()
    {
        return $this->user ? $this->user->getLinkedIn() : null;
    }

    /**
     * Returns user gplus.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userGplus")
     *
     * @return string
     */
    public function getUserGplus()
    {
        return $this->user ? $this->user->getGplus() : null;
    }

    /**
     * Returns user email.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userEmail")
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user ? $this->user->getEmail() : null;
    }

    /**
     * Returns user phone.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userPhone")
     *
     * @return string
     */
    public function getUserPhone()
    {
        return $this->user ? $this->user->getPhone() : null;
    }

    /**
     * Returns project id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->project ? $this->project->getId() : null;
    }

    /**
     * Returns project name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project ? $this->project->getName() : null;
    }

    /**
     * Returns projectCategory id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCategory")
     *
     * @return string
     */
    public function getProjectCategoryId()
    {
        return $this->projectCategory ? $this->projectCategory->getId() : null;
    }

    /**
     * Returns projectCategory name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCategoryName")
     *
     * @return string
     */
    public function getProjectCategoryName()
    {
        return $this->projectCategory ? $this->projectCategory->getName() : null;
    }

    /**
     * Returns projectRoles id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectRoles")
     *
     * @return string
     */
    public function getProjectRolesId()
    {
        $projectRolesId = [];

        if ($this->projectRoles->count()) {
            foreach ($this->projectRoles as $projectRole) {
                $projectRolesId[] = $projectRole->getId();
            }
        }

        return $projectRolesId;
    }

    /**
     * Returns projectDepartments id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectDepartments")
     *
     * @return string
     */
    public function getProjectDepartmentsId()
    {
        $projectDepartmentsId = [];

        if ($this->projectDepartments->count()) {
            foreach ($this->projectDepartments as $projectDepartment) {
                $projectDepartmentsId[] = $projectDepartment->getId();
            }
        }

        return $projectDepartmentsId;
    }

    /**
     * Returns projectTeam id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectTeam")
     *
     * @return string
     */
    public function getProjectTeamId()
    {
        return $this->projectTeam ? $this->projectTeam->getId() : null;
    }

    /**
     * Returns projectTeam name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectTeamName")
     *
     * @return string
     */
    public function getProjectTeamName()
    {
        return $this->projectTeam ? $this->projectTeam->getName() : null;
    }

    /**
     * Add projectRole.
     *
     * @param ProjectRole $projectRole
     *
     * @return ProjectUser
     */
    public function addProjectRole(ProjectRole $projectRole)
    {
        $this->projectRoles[] = $projectRole;

        return $this;
    }

    /**
     * Remove projectRole.
     *
     * @param ProjectRole $projectRole
     */
    public function removeProjectRole(ProjectRole $projectRole)
    {
        $this->projectRoles->removeElement($projectRole);
    }

    /**
     * Get projectRoles.
     *
     * @return ArrayCollection|ProjectRole[]
     */
    public function getProjectRoles()
    {
        return $this->projectRoles;
    }

    /**
     * Has role.
     *
     * @param $role
     *
     * @return bool
     */
    public function hasProjectRole($role)
    {
        foreach ($this->projectRoles as $projectRole) {
            if ($projectRole->getName() === $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add projectDepartment.
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return ProjectUser
     */
    public function addProjectDepartment(ProjectDepartment $projectDepartment)
    {
        $this->projectDepartments[] = $projectDepartment;

        return $this;
    }

    /**
     * Remove projectDepartment.
     *
     * @param ProjectDepartment $projectDepartment
     */
    public function removeProjectDepartment(ProjectDepartment $projectDepartment)
    {
        $this->projectDepartments->removeElement($projectDepartment);

        return $this;
    }

    /**
     * Get projectDepartments.
     *
     * @return ArrayCollection|ProjectRole[]
     */
    public function getProjectDepartments()
    {
        return $this->projectDepartments;
    }

    /**
     * Returns project role names.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectRoleNames")
     *
     * @return string
     */
    public function getProjectRoleNames()
    {
        $roleNames = [];
        foreach ($this->getProjectRoles() as $projectRole) {
            $roleNames[] = $projectRole->getName();
        }

        return $roleNames;
    }
}
