<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Info.
 *
 * @ORM\Table(name="info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InfoRepository")
 */
class Info
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
     * @var Project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="infos")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotBlank(message="not_blank.project")
     * @Serializer\Exclude()
     */
    private $project;

    /**
     * @var Meeting|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="infos")
     * @ORM\JoinColumn(name="meeting_id", onDelete="CASCADE")
     */
    private $meeting;

    /**
     * @var InfoCategory
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InfoCategory")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="info_category_id", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotBlank(message="not_blank.info_category")
     * @Serializer\Exclude()
     */
    private $infoCategory;

    /**
     * @var string
     * @ORM\Column(name="topic", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="not_blank.topic")
     */
    private $topic;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var User|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="responsibility_id", onDelete="SET NULL")
     */
    private $responsibility;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="expires_at", type="date", nullable=true)
     */
    private $expiresAt;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
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
     * Set topic.
     *
     * @param string $topic
     *
     * @return Info
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic.
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Info
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Info
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
     * @return Info
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
     * Set project.
     *
     * @param Project $project
     *
     * @return Info
     */
    public function setProject(Project $project)
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
     * @return Meeting|null
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param Meeting|null $meeting
     *
     * @return Info
     */
    public function setMeeting(Meeting $meeting = null)
    {
        $this->meeting = $meeting;

        if ($meeting->getProject()) {
            $this->project = $meeting->getProject();
        }

        return $this;
    }

    /**
     * Set infoCategory.
     *
     * @param InfoCategory $infoCategory
     *
     * @return Info
     */
    public function setInfoCategory(InfoCategory $infoCategory)
    {
        $this->infoCategory = $infoCategory;

        return $this;
    }

    /**
     * Get infoCategory.
     *
     * @return InfoCategory
     */
    public function getInfoCategory()
    {
        return $this->infoCategory;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return Info
     */
    public function setResponsibility(User $responsibility = null)
    {
        $this->responsibility = $responsibility;

        return $this;
    }

    /**
     * Get responsibility.
     *
     * @return User
     */
    public function getResponsibility()
    {
        return $this->responsibility;
    }

    /**
     * Returns the responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibility")
     *
     * @return string
     */
    public function getResponsibilityId()
    {
        return $this->responsibility ? $this->responsibility->getId() : null;
    }

    /**
     * Returns the responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityFullName")
     *
     * @return string
     */
    public function getResponsibilityFullName()
    {
        return $this->responsibility ? $this->responsibility->getFullName() : null;
    }

    /**
     * Returns the responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityAvatar")
     *
     * @return string
     */
    public function getResponsibilityAvatar()
    {
        return $this->responsibility ? $this->responsibility->getAvatar() : null;
    }

    /**
     * Returns the responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityGravatar")
     *
     * @return string
     */
    public function getResponsibilityGravatar()
    {
        return $this->responsibility ? $this->responsibility->getGravatar() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     */
    public function getProjectId()
    {
        return $this->project
            ? $this->project->getId()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     */
    public function getProjectName()
    {
        return $this->project
            ? $this->project->getName()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meeting")
     */
    public function getMeetingId()
    {
        return $this->meeting
            ? $this->meeting->getId()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meetingName")
     */
    public function getMeetingName()
    {
        return $this->meeting
            ? $this->meeting->getName()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("infoCategory")
     */
    public function getInfoCategoryId()
    {
        return $this->infoCategory
            ? $this->infoCategory->getId()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("infoCategoryName")
     */
    public function getInfoCategoryName()
    {
        return $this->infoCategory
            ? $this->infoCategory->getName()
            : null
        ;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime|null $expiresAt
     */
    public function setExpiresAt(\DateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        $expiresAt = $this->getExpiresAt();
        if (!$expiresAt) {
            return false;
        }

        $expiresAt->setTime(23, 59, 59);
        $now = new \DateTime();
        $now->setTime(23, 59, 59);

        return $expiresAt < $now;
    }
}
