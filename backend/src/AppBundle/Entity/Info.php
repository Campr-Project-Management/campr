<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotBlank(message="not_blank.project")
     * @Serializer\Exclude()
     */
    private $project;

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
     * @var ArrayCollection|User[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinTable(
     *     name="info_user",
     *     joinColumns={
     *         @ORM\JoinColumn(name="info_id", referencedColumnName="id", nullable=false)
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *     }
     * )
     * @Serializer\Exclude()
     */
    private $users;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="date")
     *
     * @Assert\NotBlank(message="not_blank.expiry_date")
     */
    private $expiryDate;

    /**
     * @var InfoStatus
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\InfoStatus")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="info_status_id", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotBlank(message="not_blank.info_status")
     * @Serializer\Exclude()
     */
    private $infoStatus;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();
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
     * Set expiryDate.
     *
     * @param \DateTime $expiryDate
     *
     * @return Info
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate.
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
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
     * @param \AppBundle\Entity\Project $project
     *
     * @return Info
     */
    public function setProject(\AppBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
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
     * Add user.
     *
     * @param User $user
     *
     * @return Info
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
     * Set infoStatus.
     *
     * @param InfoStatus $infoStatus
     *
     * @return Info
     */
    public function setInfoStatus(InfoStatus $infoStatus)
    {
        $this->infoStatus = $infoStatus;

        return $this;
    }

    /**
     * Get infoStatus.
     *
     * @return InfoStatus
     */
    public function getInfoStatus()
    {
        return $this->infoStatus;
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
     * @Serializer\SerializedName("infoStatus")
     */
    public function getInfoStatusId()
    {
        return $this->infoStatus
            ? $this->infoStatus->getId()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("infoStatusName")
     */
    public function getInfoStatusName()
    {
        return $this->infoStatus
            ? $this->infoStatus->getName()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("infoStatusColor")
     */
    public function getInfoStatusColor()
    {
        return $this->infoStatus
            ? $this->infoStatus->getColor()
            : '#ffffff'
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
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("users")
     */
    public function getUsersIds()
    {
        return $this->users->map(function (User $user) {
            return $user->getId();
        });
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("usersNames")
     */
    public function getUsersNames()
    {
        return $this->users->map(function (User $user) {
            return $user->getFullName();
        });
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("usersAvatars")
     */
    public function getUsersAvatars()
    {
        return $this->users->map(function (User $user) {
            return $user->getAvatar();
        });
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("usersGravatars")
     */
    public function getUsersGravatars()
    {
        return $this->users->map(function (User $user) {
            return $user->getGravatar();
        });
    }
}
