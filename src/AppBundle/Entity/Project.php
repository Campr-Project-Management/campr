<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Project.
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @UniqueEntity(fields="number", message="validation.constraints.project.number.unique")
 */
class Project
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
     * @ORM\Column(name="number", type="string", length=128, unique=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="encryption_key", type="string", length=128, nullable=true)
     */
    private $encryptionKey;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sponsor_id", referencedColumnName="id")
     * })
     */
    private $sponsor;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    /**
     * @var Company
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var ProjectComplexity
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectComplexity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_complexity_id", referencedColumnName="id")
     * })
     */
    private $projectComplexity;

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
     * @var ProjectScope
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectScope")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_scope_id", referencedColumnName="id")
     * })
     */
    private $projectScope;

    /**
     * @var ProjectStatus
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var Portfolio
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id")
     * })
     */
    private $portfolio;

    /**
     * @var ArrayCollection|Calendar[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Calendar", mappedBy="project")
     */
    private $calendars;

    /**
     * @var ArrayCollection|ChatRoom[]
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\ChatRoom", mappedBy="project")
     */
    private $chatRooms;

    /**
     * @var ArrayCollection|Message[]
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="project")
     */
    private $messages;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="project")
     */
    private $workPackages;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="status_updated_at", type="datetime", nullable=true)
     */
    private $statusUpdatedAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * @var ArrayCollection|FileSystem[]
     *
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FileSystem", mappedBy="project")
     */
    private $fileSystems;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->workPackages = new ArrayCollection();
        $this->fileSystems = new ArrayCollection();
        $this->chatRooms = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->setEncryptionKey(base64_encode(random_bytes(16)));
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
     * @return Project
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
     * Set number.
     *
     * @param string $number
     *
     * @return Project
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set statusUpdatedAt.
     *
     * @param \DateTime $statusUpdatedAt
     *
     * @return Project
     */
    public function setStatusUpdatedAt(\DateTime $statusUpdatedAt = null)
    {
        $this->statusUpdatedAt = $statusUpdatedAt;

        return $this;
    }

    /**
     * Get statusUpdatedAt.
     *
     * @return \DateTime
     */
    public function getStatusUpdatedAt()
    {
        return $this->statusUpdatedAt;
    }

    /**
     * Set approvedAt.
     *
     * @param \DateTime $approvedAt
     *
     * @return Project
     */
    public function setApprovedAt(\DateTime $approvedAt = null)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * Get approvedAt.
     *
     * @return \DateTime
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Project
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
     * @return Project
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
     * Set sponsor.
     *
     * @param User $sponsor
     *
     * @return Project
     */
    public function setSponsor(User $sponsor = null)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor.
     *
     * @return User
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set manager.
     *
     * @param User $manager
     *
     * @return Project
     */
    public function setManager(User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set company.
     *
     * @param Company $company
     *
     * @return Project
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set projectComplexity.
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return Project
     */
    public function setProjectComplexity(ProjectComplexity $projectComplexity = null)
    {
        $this->projectComplexity = $projectComplexity;

        return $this;
    }

    /**
     * Get projectComplexity.
     *
     * @return ProjectComplexity
     */
    public function getProjectComplexity()
    {
        return $this->projectComplexity;
    }

    /**
     * Set projectCategory.
     *
     * @param ProjectCategory $projectCategory
     *
     * @return Project
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
     * Set projectScope.
     *
     * @param ProjectScope $projectScope
     *
     * @return Project
     */
    public function setProjectScope(ProjectScope $projectScope = null)
    {
        $this->projectScope = $projectScope;

        return $this;
    }

    /**
     * Get projectScope.
     *
     * @return ProjectScope
     */
    public function getProjectScope()
    {
        return $this->projectScope;
    }

    /**
     * Set status.
     *
     * @param ProjectStatus $status
     *
     * @return Project
     */
    public function setStatus(ProjectStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return ProjectStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set portfolio.
     *
     * @param Portfolio $portfolio
     *
     * @return Project
     */
    public function setPortfolio(Portfolio $portfolio = null)
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    /**
     * Get portfolio.
     *
     * @return Portfolio
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    /**
     * Add calendar.
     *
     * @param Calendar $calendar
     *
     * @return Project
     */
    public function addCalendar(Calendar $calendar)
    {
        $this->calendars[] = $calendar;

        return $this;
    }

    /**
     * Remove calendar.
     *
     * @param Calendar $calendar
     *
     * @return Project
     */
    public function removeCalendar(Calendar $calendar)
    {
        $this->calendars->removeElement($calendar);

        return $this;
    }

    /**
     * Get calendars.
     *
     * @return ArrayCollection
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * Add workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return Project
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
     *
     * @return Project
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

    /**
     * Returns project category name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCategory")
     *
     * @return string
     */
    public function getProjectCategoryName()
    {
        return $this->projectCategory ? $this->projectCategory->getName() : null;
    }

    /**
     * Returns status name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("status")
     *
     * @return string
     */
    public function getStatusName()
    {
        return $this->status ? $this->status->getName() : null;
    }

    /**
     * Returns portfolio name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("portfolio")
     *
     * @return string
     */
    public function getPortfolioName()
    {
        return $this->portfolio ? $this->portfolio->getName() : null;
    }

    /**
     * Add FileSystem.
     *
     * @param FileSystem $fileSystem
     *
     * @return Project
     */
    public function addFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystems[] = $fileSystem;

        return $this;
    }

    /**
     * Add chatRoom.
     *
     * @param ChatRoom $chatRoom
     *
     * @return Project
     */
    public function addChatRoom(ChatRoom $chatRoom)
    {
        $this->chatRooms[] = $chatRoom;

        return $this;
    }

    /**
     * Set encryptionKey.
     *
     * @param string $encryptionKey
     *
     * @return Project
     */
    public function setEncryptionKey($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;

        return $this;
    }

    /**
     * Remove fileSystem.
     *
     * @param FileSystem $fileSystem
     *
     * @return Project
     */
    public function removeFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystems->removeElement($fileSystem);

        return $this;
    }

    /**
     * Remove chatRoom.
     *
     * @param ChatRoom $chatRoom
     */
    public function removeChatRoom(ChatRoom $chatRoom)
    {
        $this->chatRooms->removeElement($chatRoom);

        return $this;
    }

    /**
     * Get rooms.
     *
     * @return ArrayCollection
     */
    public function getChatRooms()
    {
        return $this->chatRooms;
    }

    /**
     * Add message.
     *
     * @param Message $message
     *
     * @return Project
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message.
     *
     * @param Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);

        return $this;
    }

    /**
     * Get fileSystems.
     *
     * @return ArrayCollection
     */
    public function getFileSystems()
    {
        return $this->fileSystems;
    }

    /**
     * Get messages.
     *
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get encryptionKey.
     *
     * @return string
     */
    public function getEncryptionKey()
    {
        return $this->encryptionKey;
    }
}
