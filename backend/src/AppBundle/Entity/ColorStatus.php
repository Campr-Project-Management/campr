<?php

namespace AppBundle\Entity;

use Component\Resource\Model\CodeAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Color Status - green/yellow/red.
 *
 * @ORM\Table(name="color_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ColorStatusRepository")
 * @UniqueEntity(fields="name", message="unique.code")
 */
class ColorStatus implements CodeAwareInterface
{
    const STATUS_NOT_STARTED = 'color_status.not_started';
    const STATUS_IN_PROGRESS = 'color_status.in_progress';
    const STATUS_FINISHED = 'color_status.finished';

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
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7)
     */
    private $color;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="colorStatus")
     * @Serializer\Exclude()
     */
    private $workPackages;

    public function __construct()
    {
        $this->workPackages = new ArrayCollection();
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
     * @return ColorStatus
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
     * Set color.
     *
     * @param string $color
     *
     * @return ColorStatus
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return ColorStatus
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence.
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return ColorStatus
     */
    public function addWorkPackage(WorkPackage $workPackage): self
    {
        $this->workPackages[] = $workPackage;
        $workPackage->setColorStatus($this);

        return $this;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return ColorStatus
     */
    public function removeWorkPackage(WorkPackage $workPackage): self
    {
        $this->workPackages->removeElement($workPackage);
        $workPackage->setColorStatus(null);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getWorkPackages(): Collection
    {
        return $this->workPackages;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(string $code = null)
    {
        $this->code = $code;
    }
}
