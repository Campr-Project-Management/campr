<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Log.
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
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
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="class", type="string", length=255)
     */
    private $class;

    /**
     * @var int
     *
     * @ORM\Column(name="obj_id", type="integer")
     */
    private $objId;

    /**
     * @var array
     *
     * @ORM\Column(name="old_value", type="array")
     */
    private $oldValue;

    /**
     * @var array
     *
     * @ORM\Column(name="new_value", type="array")
     */
    private $newValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Returns string containing user email, log class and createdAt formatted.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s, %s, %s',
            $this->user->getEmail(),
            $this->class,
            $this->createdAt->format('d.m.Y H:i:s')
        );
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
     * Set class.
     *
     * @param string $class
     *
     * @return Log
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set objId.
     *
     * @param int $objId
     *
     * @return Log
     */
    public function setObjId($objId)
    {
        $this->objId = $objId;

        return $this;
    }

    /**
     * Get objId.
     *
     * @return int
     */
    public function getObjId()
    {
        return $this->objId;
    }

    /**
     * Set oldValue.
     *
     * @param array $oldValue
     *
     * @return Log
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    /**
     * Get oldValue.
     *
     * @return array
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * Set newValue.
     *
     * @param array $newValue
     *
     * @return Log
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;

        return $this;
    }

    /**
     * Get newValue.
     *
     * @return array
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Log
     */
    public function setCreatedAt(\DateTime $createdAt = null)
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
     * Set user.
     *
     * @param User $user
     *
     * @return Log
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
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFullName")
     */
    public function getUserFullName()
    {
        return $this->user->getFullName();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userUsername")
     */
    public function getUserUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userEmail")
     */
    public function getUserEmail()
    {
        return $this->user->getEmail();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userId")
     */
    public function getUserId()
    {
        return $this->user->getId();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isCommentAdded")
     */
    public function isCommentAdded()
    {
        $newValueObj = $this->getNewValue();

        return isset($newValueObj['comment']);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isResponsibilityAdded")
     */
    public function isResponsibilityAdded()
    {
        $newValueObj = $this->getNewValue();

        return isset($newValueObj['responsibility']);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isLabelAdded")
     */
    public function isLabelAdded()
    {
        $newValueObj = $this->getNewValue();

        return isset($newValueObj['label']);
    }

    /**
     * Returns true if there is a field that was
     * edited other than 'updatedAt'.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isFieldEdited")
     */
    public function isFieldEdited()
    {
        $newValueObj = $this->getNewValue();
        unset($newValueObj['updatedAt']);

        return count($newValueObj) > 0;
    }
}
