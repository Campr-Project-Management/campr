<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResourceRepository")
 * @ORM\Table(name="resource")
 * @UniqueEntity(fields={"name"}, message="unique.name")
 */
class Resource
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Project|null
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="resources")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $project;

    /**
     * @var ProjectUser|null
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectUser", inversedBy="resources")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_user_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $projectUser;

    /**
     * @var float
     * @ORM\Column(name="rate", type="decimal", precision=9, scale=2)
     * @Assert\NotBlank(message="not_blank.rate")
     */
    private $rate;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="not_blank.name")
     */
    private $name;

    /**
     * @var Cost[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cost", mappedBy="resource")
     * @Serializer\Exclude()
     */
    private $costs;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Cost $cost
     *
     * @return resource
     */
    public function addCost(Cost $cost)
    {
        $this->costs[] = $cost;

        return $this;
    }

    /**
     * @param Cost $cost
     *
     * @return resource
     */
    public function removeCost(Cost $cost)
    {
        $this->costs->removeElement($cost);

        return $this;
    }

    /**
     * @return Cost[]|ArrayCollection
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     *
     * @return resource
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     *
     * @return resource
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectId")
     *
     * @return int|null
     */
    public function getProjectId()
    {
        return $this->project ? $this->project->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     *
     * @return string|null
     */
    public function getProjectName()
    {
        return $this->project ? (string) $this->project : null;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return resource
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return ProjectUser|null
     */
    public function getProjectUser()
    {
        return $this->projectUser;
    }

    /**
     * @param ProjectUser|null $projectUser
     *
     * @return resource
     */
    public function setProjectUser(ProjectUser $projectUser = null)
    {
        $this->projectUser = $projectUser;

        return $this;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectUserId")
     *
     * @return int|null
     */
    public function getProjectUserId()
    {
        return $this->projectUser ? $this->projectUser->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectUserRate")
     *
     * @return string|null
     */
    public function getProjectUserRate()
    {
        return $this->projectUser ? (string) $this->projectUser->getRate() : null;
    }
}
