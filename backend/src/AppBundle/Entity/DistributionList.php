<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\BlameableInterface;
use Component\Resource\Model\BlameableTrait;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DistributionList.
 *
 * @ORM\Table(name="distribution_list", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="name_project_unique", columns={"name", "project_id"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DistributionListRepository")
 * @UniqueEntity(
 *     fields={"name","project"},
 *     errorPath="name",
 *     message="unique.name"
 *  )
 */
class DistributionList implements TimestampableInterface, BlameableInterface, ProjectAwareInterface, ResourceInterface, CloneableInterface
{
    use TimestampableTrait, BlameableTrait;

    const STATUS_REPORT_DISTRIBUTION = 'label.status_report_distribution';

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
     * @var int
     *
     * @Gedmo\SortableGroup
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var int
     *
     * @Serializer\Exclude()
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Project|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="distributionLists")
     * @ORM\JoinColumn(name="project_id", nullable=false, onDelete="CASCADE")
     */
    private $project;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="distributionLists")
     * @ORM\JoinTable(
     *     name="distribution_list_user",
     *     joinColumns={
     *         @ORM\JoinColumn(name="distribution_list_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $users;

    /**
     * @var ArrayCollection|Meeting[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Meeting", inversedBy="distributionLists")
     * @ORM\JoinTable(
     *     name="distribution_list_meeting",
     *     joinColumns={
     *         @ORM\JoinColumn(name="distribution_list_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="meeting_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $meetings;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ownedDistributionLists")
     * @ORM\JoinColumn(name="user_id", nullable=false, onDelete="CASCADE")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Decision", mappedBy="distributionList", cascade={"all"})
     */
    private $decisions;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="distributionList", cascade={"all"})
     */
    private $todos;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Info", mappedBy="distributionList", cascade={"all"})
     */
    private $infos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->infos = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
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
     * @return DistributionList
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
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return DistributionList
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
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return DistributionList
     */
    public function setProject(ProjectInterface $project = null)
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
     * Add user.
     *
     * @param User $user
     *
     * @return DistributionList
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Get users.
     *
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add meeting.
     *
     * @param Meeting $meeting
     *
     * @return DistributionList
     */
    public function addMeeting(Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Remove meeting.
     *
     * @param Meeting $meeting
     */
    public function removeMeeting(Meeting $meeting)
    {
        $this->meetings->removeElement($meeting);
    }

    /**
     * Get meetings.
     *
     * @return ArrayCollection|Meeting[]
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return DistributionList
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set position.
     *
     * @param int $position
     *
     * @return DistributionList
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
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
     * Returns createdBy id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdBy")
     *
     * @return string
     */
    public function getCreatedById()
    {
        return $this->createdBy ? $this->createdBy->getId() : null;
    }

    /**
     * Returns createdBy fullname.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdByFullName")
     *
     * @return string
     */
    public function getCreatedByFullName()
    {
        return $this->createdBy ? $this->createdBy->getFullName() : null;
    }

    /**
     * Returns createdBy departments.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdByDepartmentNames")
     *
     * @return string
     */
    public function getCreatedByDepartmentNames()
    {
        if ($this->createdBy) {
            foreach ($this->createdBy->getProjectUsers() as $projectUser) {
                if ($projectUser->getProject() === $this->getProject()) {
                    return $projectUser->getProjectDepartmentNames();
                }
            }
        }

        return [];
    }

    /**
     * Add decision.
     *
     * @param Decision $decision
     */
    public function addDecision(Decision $decision)
    {
        $this->decisions[] = $decision;
        $decision->setMeeting($this);
    }

    /**
     * Remove decision.
     *
     * @param Decision $decision
     */
    public function removeDecision(Decision $decision)
    {
        $this->decisions->removeElement($decision);
    }

    /**
     * Get decisions.
     *
     * @return ArrayCollection|Decision[]
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

    /**
     * Add todo.
     *
     * @param Todo $todo
     */
    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;
        $todo->setDistributionList($this);
    }

    /**
     * Remove todo.
     *
     * @param Todo $todo
     */
    public function removeTodo(Todo $todo)
    {
        $this->todos->removeElement($todo);
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
     * Add Info.
     *
     * @param Info $info
     */
    public function addInfo(Info $info)
    {
        $this->infos[] = $info;
        $info->setMeeting($this);
    }

    /**
     * Remove Info.
     *
     * @param Info $info
     */
    public function removeInfo(Info $info)
    {
        $this->infos->removeElement($info);
    }

    /**
     * Get Infos.
     *
     * @return ArrayCollection|Info[]
     */
    public function getInfos()
    {
        return $this->infos;
    }
}
