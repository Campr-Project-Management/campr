<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WorkPackage.
 *
 * @ORM\Table(name="programme")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProgrammeRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 */
class Programme
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection|Project[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Project", mappedBy="programme")
     */
    private $projects;

    /**
     * Programme constructor.
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Project[]|ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add Project.
     *
     * @param Project $project
     *
     * @return Programme
     */
    public function addProject(Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove Project.
     *
     * @param Project $project
     *
     * @return Programme
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);

        return $this;
    }
}
