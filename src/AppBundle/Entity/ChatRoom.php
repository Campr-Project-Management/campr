<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule.
 *
 * @ORM\Table(name="chat_room")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChatRoomRepository")
 */
class ChatRoom
{
    const GENERAL_ROOM = '#general';

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
     * @var Project|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="chatRooms", cascade={"remove"})
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false, options={"default"= 1})
     */
    private $isDefault = true;

    /**
     * @var ArrayCollection|Message[]
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="chatRoom")
     */
    private $messages;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->messages = new ArrayCollection();
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
     * @return Room
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
     * Set isDefault.
     *
     * @param bool $isDefault
     *
     * @return Room
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Room
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
     * Set project.
     *
     * @param Project $project
     *
     * @return Room
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
     * Add message.
     *
     * @param Message $message
     *
     * @return Room
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
     * Get messages.
     *
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function getLastMessage()
    {
        return $this->messages->last();
    }
}
