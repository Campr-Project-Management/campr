<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * ProjectScope.
 *
 * @ORM\Table(name="project_scope")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectScopeRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @Cloner\Exclude()
 */
class ProjectScope implements ResourceInterface, CloneableInterface, TimestampableInterface
{
    use TimestampableTrait;

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
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * ProjectScope constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return (string) $this->name;
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
     * @return ProjectScope
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
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return ProjectScope
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
}
