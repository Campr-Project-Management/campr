<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Status Report.
 *
 * @ORM\Table(name="status_report_config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusReportConfigRepository")
 */
class StatusReportConfig
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="statusReportConfigs")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var int
     *
     * @ORM\Column(name="per_day", type="integer", nullable=false)
     */
    private $perDay;

    /**
     * @var int
     *
     * @ORM\Column(name="minutes_interval", type="integer", nullable=true)
     */
    private $minutesInterval;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=false, options={"default"= 0})
     */
    private $isDefault = false;

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
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerDay()
    {
        return $this->perDay;
    }

    /**
     * @param int $perDay
     */
    public function setPerDay($perDay)
    {
        $this->perDay = $perDay;
    }

    /**
     * @return int
     */
    public function getMinutesInterval()
    {
        return $this->minutesInterval;
    }

    /**
     * @param int $minutesInterval
     */
    public function setMinutesInterval($minutesInterval)
    {
        $this->minutesInterval = $minutesInterval;
    }

    /**
     * @return bool
     */
    public function isIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
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
}
