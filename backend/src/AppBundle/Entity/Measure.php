<?php

namespace AppBundle\Entity;

use Component\Project\ProjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Measure.
 *
 * @ORM\Table(name="measure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeasureRepository")
 */
class Measure
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var User|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id")
     */
    private $responsibility;

    /**
     * @var Media[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Media", inversedBy="measures", cascade={"all"})
     * @ORM\JoinTable(
     *     name="measure_media",
     *     joinColumns={
     *         @ORM\JoinColumn(name="measure_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="media_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $medias;

    /**
     * @var ArrayCollection|MeasureComment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeasureComment", mappedBy="measure")
     */
    private $comments;

    /**
     * @var Risk|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Risk", inversedBy="measures")
     * @ORM\JoinColumn(name="risk_id", onDelete="CASCADE")
     */
    private $risk;

    /**
     * @var Opportunity|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Opportunity", inversedBy="measures")
     * @ORM\JoinColumn(name="opportunity_id", onDelete="CASCADE")
     */
    private $opportunity;

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

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->medias = new ArrayCollection();
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
     * @param string $title
     *
     * @return Measure
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Measure
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
     * Set cost.
     *
     * @param int $cost
     *
     * @return Measure
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param Media $media
     *
     * @return Measure
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * @param Media $media
     *
     * @return Measure
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);

        return $this;
    }

    /**
     * @return Media[]|ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Measure
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
     * @return Measure
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
     * @param MeasureComment $comment
     *
     * @return Measure
     */
    public function addComment(MeasureComment $comment)
    {
        $comment->setMeasure($this);
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param MeasureComment $comment
     *
     * @return Measure
     */
    public function removeComment(MeasureComment $comment)
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * @return Media[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return Measure
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
     * Set risk.
     *
     * @param Risk|null $risk
     *
     * @return Measure
     */
    public function setRisk(Risk $risk = null)
    {
        $this->risk = $risk;

        return $this;
    }

    /**
     * Get measure.
     *
     * @return Risk|null
     */
    public function getRisk()
    {
        return $this->risk;
    }

    /**
     * Set opportunity.
     *
     * @param Opportunity|null $opportunity
     *
     * @return Measure
     */
    public function setOpportunity(Opportunity $opportunity = null)
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    /**
     * Get opportunity.
     *
     * @return Opportunity|null
     */
    public function getOpportunity()
    {
        return $this->opportunity;
    }

    /**
     * Returns responsibility id.
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
     * Returns responsibility full name.
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
     * Returns responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityUsername")
     *
     * @return string
     */
    public function getResponsibilityUsername()
    {
        return $this->responsibility ? $this->responsibility->getUsername() : null;
    }

    /**
     * @return ProjectInterface|null
     */
    public function getProject()
    {
        if ($this->getRisk()) {
            return $this->getRisk()->getProject();
        }

        if ($this->getOpportunity()) {
            return $this->getOpportunity()->getProject();
        }

        return null;
    }
}
