<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectCloseDown.
 *
 * @ORM\Table(name="project_close_down")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectCloseDownRepository")
 */
class ProjectCloseDown
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projectCloseDowns")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="overall_impression", type="text", nullable=true)
     */
    private $overallImpression;

    /**
     * @var string
     *
     * @ORM\Column(name="performance_schedule", type="text", nullable=true)
     */
    private $performanceSchedule;

    /**
     * @var string
     *
     * @ORM\Column(name="organization_context", type="text", nullable=true)
     */
    private $organizationContext;

    /**
     * @var string
     *
     * @ORM\Column(name="project_management", type="text", nullable=true)
     */
    private $projectManagement;

    /**
     * @var bool
     *
     * @ORM\Column(name="frozen", type="boolean", nullable=false, options={"default"=0})
     */
    private $frozen = false;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var ArrayCollection|EvaluationObjective[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EvaluationObjective", mappedBy="projectCloseDown")
     */
    private $evaluationObjectives;

    /**
     * @var ArrayCollection|Lesson[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lesson", mappedBy="projectCloseDown")
     */
    private $lessons;

    /**
     * @var ArrayCollection|CloseDownAction[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CloseDownAction", mappedBy="projectCloseDown")
     */
    private $closeDownActions;

    /**
     * ProjectCloseDown constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->evaluationObjectives = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->closeDownActions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOverallImpression()
    {
        return $this->overallImpression;
    }

    /**
     * @param string $overallImpression
     */
    public function setOverallImpression($overallImpression)
    {
        $this->overallImpression = $overallImpression;

        return $this;
    }

    /**
     * @return string
     */
    public function getPerformanceSchedule()
    {
        return $this->performanceSchedule;
    }

    /**
     * @param string $performanceSchedule
     */
    public function setPerformanceSchedule($performanceSchedule)
    {
        $this->performanceSchedule = $performanceSchedule;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrganizationContext()
    {
        return $this->organizationContext;
    }

    /**
     * @param string $organizationContext
     */
    public function setOrganizationContext($organizationContext)
    {
        $this->organizationContext = $organizationContext;

        return $this;
    }

    /**
     * @return string
     */
    public function getProjectManagement()
    {
        return $this->projectManagement;
    }

    /**
     * @param string $projectManagement
     */
    public function setProjectManagement($projectManagement)
    {
        $this->projectManagement = $projectManagement;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFrozen()
    {
        return $this->frozen;
    }

    /**
     * @param bool $frozen
     */
    public function setFrozen($frozen)
    {
        $this->frozen = $frozen;

        return $this;
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
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return Opportunity
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
     * Returns project name.
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
     * Get evaluationObjectives.
     *
     * @return ArrayCollection|EvaluationObjective[]
     */
    public function getEvaluationObjectives()
    {
        return $this->evaluationObjectives;
    }

    /**
     * Add evaluationObjective.
     *
     * @param EvaluationObjective $evaluationObjective
     *
     * @return ProjectCloseDown
     */
    public function addEvaluationObjective(EvaluationObjective $evaluationObjective)
    {
        $this->evaluationObjectives[] = $evaluationObjective;

        return $this;
    }

    /**
     * Remove evaluationObjective.
     *
     * @param EvaluationObjective $evaluationObjective
     */
    public function removeProjectCloseDown(EvaluationObjective $evaluationObjective)
    {
        $this->evaluationObjectives->removeElement($evaluationObjective);

        return $this;
    }

    /**
     * Get lessons.
     *
     * @return ArrayCollection|Lesson[]
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * Add lesson.
     *
     * @param Lesson $lesson
     *
     * @return ProjectCloseDown
     */
    public function addLesson(Lesson $lesson)
    {
        $this->lessons[] = $lesson;

        return $this;
    }

    /**
     * Remove lesson.
     *
     * @param Lesson $lesson
     */
    public function removeLesson(Lesson $lesson)
    {
        $this->lessons->removeElement($lesson);

        return $this;
    }

    /**
     * Get closeDownAction.
     *
     * @return ArrayCollection|CloseDownAction[]
     */
    public function getCloseDownActions()
    {
        return $this->closeDownActions;
    }

    /**
     * Add closeDownAction.
     *
     * @param CloseDownAction $closeDownAction
     *
     * @return ProjectCloseDown
     */
    public function addCloseDownAction(CloseDownAction $closeDownAction)
    {
        $this->closeDownActions[] = $closeDownAction;

        return $this;
    }

    /**
     * Remove closeDownAction.
     *
     * @param CloseDownAction $closeDownAction
     */
    public function removeCloseDownAction(CloseDownAction $closeDownAction)
    {
        $this->closeDownActions->removeElement($closeDownAction);

        return $this;
    }
}
