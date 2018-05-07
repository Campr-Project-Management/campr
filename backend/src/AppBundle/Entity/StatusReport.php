<?php

namespace AppBundle\Entity;

use Component\Model\SnapshotAwareInterface;
use Component\Snapshot\Snapshot;
use Component\Snapshot\SnapshotInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Status Report.
 *
 * @ORM\Table(name="status_report")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusReportRepository")
 */
class StatusReport implements SnapshotAwareInterface
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
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="statusReports")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var array
     *
     * @ORM\Column(name="information", type="json_array", nullable=true)
     */
    private $information;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="project_action_needed", type="boolean", nullable=false, options={"default": false})
     */
    private $projectActionNeeded;

    /**
     * @var int
     *
     * @ORM\Column(name="project_traffic_light", type="integer", nullable=true)
     * @Assert\NotNull()
     * @Assert\Choice(callback={"Component\TrafficLight\TrafficLight", "getValues"})
     */
    private $projectTrafficLight;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="statusReports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * StatusReport constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->projectActionNeeded = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     *
     * @return $this
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get information.
     *
     * @return array
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param array $information
     *
     * @return $this
     */
    public function setInformation(array $information = [])
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return StatusReport
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * @return string
     */
    public function getComment(): string
    {
        return (string) $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment = null)
    {
        $this->comment = $comment;
    }

    /**
     * @return SnapshotInterface
     */
    public function getSnapshot(): SnapshotInterface
    {
        return new Snapshot($this->getInformation());
    }

    /**
     * @param SnapshotInterface $snapshot
     */
    public function setSnapshot(SnapshotInterface $snapshot)
    {
        $this->setInformation($snapshot->toArray());
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int
     */
    public function getWeekNumber(): int
    {
        if (!$this->createdAt) {
            return 0;
        }

        return (int) $this->createdAt->format('W');
    }

    /**
     * @return bool
     */
    public function isProjectActionNeeded(): bool
    {
        return (bool) $this->projectActionNeeded;
    }

    /**
     * @param bool $projectActionNeeded
     */
    public function setProjectActionNeeded(bool $projectActionNeeded = null)
    {
        $this->projectActionNeeded = (bool) $projectActionNeeded;
    }

    /**
     * @return int|null
     */
    public function getProjectTrafficLight()
    {
        return $this->projectTrafficLight;
    }

    /**
     * @param int $projectTrafficLight
     */
    public function setProjectTrafficLight(int $projectTrafficLight = null)
    {
        $this->projectTrafficLight = $projectTrafficLight;
    }
}
