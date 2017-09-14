<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * EvaluationObjective.
 *
 * @ORM\Table(name="evaluation_objective")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvaluationObjectiveRepository")
 */
class EvaluationObjective
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
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var Contract
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectCloseDown", inversedBy="evaluationObjectives")
     * @ORM\JoinColumn(name="project_close_down_id", referencedColumnName="id")
     */
    private $projectCloseDown;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return ProjectCloseDown
     */
    public function getProjectCloseDown()
    {
        return $this->projectCloseDown;
    }

    /**
     * @param ProjectCloseDown $projectCloseDown
     */
    public function setProjectCloseDown(ProjectCloseDown $projectCloseDown = null)
    {
        $this->projectCloseDown = $projectCloseDown;

        return $this;
    }

    /**
     * Returns project close down id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCloseDown")
     *
     * @return int
     */
    public function getProjectCloseDownId()
    {
        return $this->projectCloseDown ? $this->projectCloseDown->getId() : null;
    }

    /**
     * Returns project name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     *
     * @return int
     */
    public function getProjectName()
    {
        return $this->projectCloseDown ? $this->projectCloseDown->getProject() ? $this->projectCloseDown->getProject()->getName() : null : null;
    }
}
