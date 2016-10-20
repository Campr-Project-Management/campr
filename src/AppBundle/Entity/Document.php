<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Document.
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 */
class Document
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
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var ArrayCollection|Meeting[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Meeting", inversedBy="documents")
     * @ORM\JoinTable(
     *     name="document_meeting",
     *     joinColumns={
     *         @ORM\JoinColumn(name="document_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="meeting_id")
     *     }
     * )
     */
    private $meetings;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false, unique=true)
     */
    private $path;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->meetings = new ArrayCollection();
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
     * Set path.
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return Document
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
     * Add meeting.
     *
     * @param Meeting $meeting
     *
     * @return Document
     */
    public function addMeeting(Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Remove meeting.
     *
     * @param Meeting $meeting
     */
    public function removeMeeting(Meeting $meeting)
    {
        $this->meetings->removeElement($meeting);
    }

    /**
     * Get meetings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }
}
