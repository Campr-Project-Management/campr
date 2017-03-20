<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Project.
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @UniqueEntity(fields="number", message="unique.number")
 * @Vich\Uploadable
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
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="encryption_key", type="string", length=128, nullable=true)
     */
    private $encryptionKey;

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
     * @var ArrayCollection|ProjectUser[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectUser", mappedBy="project")
     */
    private $projectUsers;

    /**
     * @var ArrayCollection|ProjectTeam[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectTeam", mappedBy="project")
     */
    private $projectTeams;

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
     * @var ArrayCollection|Note[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="project")
     */
    private $notes;

    /**
     * @var ArrayCollection|Todo[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="project")
     */
    private $todos;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="project")
     */
    private $distributionLists;

    /**
     * @var ArrayCollection|ChatRoom[]
     *
     * @Serializer\Exclude()
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\ChatRoom", mappedBy="project")
     */
    private $chatRooms;

    /**
     * @var ArrayCollection|Message[]
     *
     * @Serializer\Exclude()
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
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="status_updated_at", type="datetime", nullable=true)
     */
    private $statusUpdatedAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * @Vich\UploadableField(mapping="project_images", fileNameProperty="logo")
     * @Serializer\Exclude()
     *
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     *
     * @Serializer\Exclude()
     *
     * @var string
     */
    private $logo;

    /**
     * @var ArrayCollection|FileSystem[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FileSystem", mappedBy="project")
     */
    private $fileSystems;

    /**
     * @var ArrayCollection|Label[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Label", mappedBy="project")
     */
    private $labels;

    /**
     * @var ArrayCollection|Message[]
     *
     * @Serializer\Exclude()
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="project")
     */
    private $meetings;

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
     * @var ArrayCollection|Contract[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="project")
     */
    private $contracts;

    /**
     * @var ArrayCollection|User[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="favoriteProjects")
     * @ORM\JoinTable(
     *     name="project_user_favorites",
     *     joinColumns={
     *         @ORM\JoinColumn(name="project_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id")
     *     }
     * )
     */
    private $userFavorites;

    /**
     * @var Programme|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Programme", inversedBy="projects")
     * @ORM\JoinColumn(name="programme_id")
     */
    private $programme;

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
        $this->projectUsers = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->distributionLists = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->userFavorites = new ArrayCollection();
        $this->projectTeams = new ArrayCollection();
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
     * Returns company id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("company")
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->company ? $this->company->getId() : null;
    }

    /**
     * Returns company name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("companyName")
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company ? $this->company->getName() : null;
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
     * Returns projectComplexity id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectComplexity")
     *
     * @return string
     */
    public function getProjectComplexityId()
    {
        return $this->projectComplexity ? $this->projectComplexity->getId() : null;
    }

    /**
     * Returns projectComplexity name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectComplexityName")
     *
     * @return string
     */
    public function getProjectComplexityName()
    {
        return $this->projectComplexity ? $this->projectComplexity->getName() : null;
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
     * Returns projectScope id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectScope")
     *
     * @return string
     */
    public function getProjectScopeId()
    {
        return $this->projectScope ? $this->projectScope->getId() : null;
    }

    /**
     * Returns projectScope name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectScopeName")
     *
     * @return string
     */
    public function getProjectScopeName()
    {
        return $this->projectScope ? $this->projectScope->getName() : null;
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
     * Returns status id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("status")
     *
     * @return string
     */
    public function getStatusId()
    {
        return $this->status ? $this->status->getId() : null;
    }

    /**
     * Returns status name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("statusName")
     *
     * @return string
     */
    public function getStatusName()
    {
        return $this->status ? $this->status->getName() : null;
    }

    /**
     * Returns portfolio id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("portfolio")
     *
     * @return string
     */
    public function getPortfolioId()
    {
        return $this->portfolio ? $this->portfolio->getId() : null;
    }

    /**
     * Returns portfolio name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("portfolioName")
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
     *
     * @return Project
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
     *
     * @return Project
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

    /**
     * Add projectUser.
     *
     * @param ProjectUser $projectUser
     *
     * @return Project
     */
    public function addProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;

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
     * Add note.
     *
     * @param Note $note
     *
     * @return Project
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note.
     *
     * @param Note $note
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);

        return $this;
    }

    /**
     * Get notes.
     *
     * @return ArrayCollection|Note[]
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add todo.
     *
     * @param Todo $todo
     *
     * @return Project
     */
    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;

        return $this;
    }

    /**
     * Remove todo.
     *
     * @param Todo $todo
     */
    public function removeTodo(Todo $todo)
    {
        $this->todos->removeElement($todo);

        return $this;
    }

    /**
     * Get todos.
     *
     * @return ArrayCollection|Todo[]
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * Set logoFile.
     *
     * @param File|null $image
     *
     * @return Project
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get logoFile.
     *
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * Add distributionList.
     *
     * @param DistributionList $distributionList
     *
     * @return Project
     */
    public function addDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists[] = $distributionList;

        return $this;
    }

    /**
     * Remove distributionList.
     *
     * @param DistributionList $distributionList
     */
    public function removeDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists->removeElement($distributionList);
    }

    /**
     * Get distributionLists.
     *
     * @return ArrayCollection|DistributionList[]
     */
    public function getDistributionLists()
    {
        return $this->distributionLists;
    }

    /**
     * Add label.
     *
     * @param Label $label
     *
     * @return Project
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * Remove label.
     *
     * @param Label $label
     */
    public function removeLabel(Label $label)
    {
        $this->labels->removeElement($label);
    }

    /**
     * Get labels.
     *
     * @return ArrayCollection|Label[]
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Add contract.
     *
     * @param Contract $contract
     *
     * @return Project
     */
    public function addContract(Contract $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param Contract $contract
     */
    public function removeContract(Contract $contract)
    {
        $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return ArrayCollection|Contract[]
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Remove meeting.
     *
     * @param Meeting $meeting
     *
     * @return Project
     */
    public function removeMeeting(Meeting $meeting)
    {
        $this->meetings->removeElement($meeting);

        return $this;
    }

    /**
     * Add meeting.
     *
     * @param Meeting $meeting
     *
     * @return Project
     */
    public function addMeeting(Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Get meetings.
     *
     * @return ArrayCollection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * Add user.
     *
     * @param User $user
     *
     * @return Project
     */
    public function addUserFavorite(User $user)
    {
        if (!$this->userFavorites->contains($user)) {
            $this->userFavorites[] = $user;
        }

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     *
     * @return Project
     */
    public function removeUserFavorite(User $user)
    {
        if ($this->userFavorites->contains($user)) {
            $this->userFavorites->removeElement($user);
        }

        return $this;
    }

    /**
     * Get users.
     *
     * @return ArrayCollection|User[]
     */
    public function getUserFavorites()
    {
        return $this->userFavorites;
    }

    /**
     * Returns user favorite ids.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFavorites")
     *
     * @return array
     */
    public function getUserFavoritesIds()
    {
        $favorites = [];
        foreach ($this->userFavorites as $userFavorite) {
            $favorites[] = $userFavorite->getId();
        }

        return $favorites;
    }

    /**
     * Add projectTeam.
     *
     * @param ProjectTeam $projectTeam
     *
     * @return Project
     */
    public function addProjectTeam(ProjectTeam $projectTeam)
    {
        $this->projectTeams[] = $projectTeam;

        return $this;
    }

    /**
     * Remove projectTeam.
     *
     * @param ProjectTeam $projectTeam
     *
     * @return Project;
     */
    public function removeProjectTeam(ProjectTeam $projectTeam)
    {
        $this->projectTeams->removeElement($projectTeam);

        return $this;
    }

    /**
     * Get projectTeams.
     *
     * @return ArrayCollection|ProjectTeam[]
     */
    public function getProjectTeams()
    {
        return $this->projectTeams;
    }

    /**
     * Get project progress.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("progress")
     *
     * @return array
     */
    public function getProgress()
    {
        $counter = 0;
        foreach ($this->getWorkPackages() as $wp) {
            $counter += $wp->getProgress();
        }

        return !$this->getWorkPackages()->isEmpty() ? $counter / $this->getWorkPackages()->count() : 0;
    }

    /**
     * @return Programme|null
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * @param Programme|null $programme
     */
    public function setProgramme(Programme $programme = null)
    {
        $this->programme = $programme;
    }

    /**
     * Returns Programme id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("programme")
     *
     * @return int
     */
    public function getProgrammeId()
    {
        return $this->programme ? $this->programme->getId() : null;
    }

    /**
     * Returns Programme name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("programmeName")
     *
     * @return string
     */
    public function getProgrammeName()
    {
        return $this->programme ? $this->programme->getName() : null;
    }
}
