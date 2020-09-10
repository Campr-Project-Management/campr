<?php

namespace AppBundle\Entity;

use AppBundle\Model\RemovalForbiddenInterface;
use AppBundle\Validator\Constraints\Sequence;
use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\CodeAwareInterface;
use Component\Resource\Model\CodeableTrait;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\SequenceableInterface;
use Component\Resource\Model\SequenceableTrait;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * ProjectStatus.
 *
 * @ORM\Table(name="project_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectStatusRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @Sequence()
 * @Cloner\Exclude()
 */
class ProjectStatus implements RemovalForbiddenInterface, ResourceInterface, CloneableInterface, TimestampableInterface, SequenceableInterface, CodeAwareInterface
{
    use TimestampableTrait;
    use SequenceableTrait;
    use CodeableTrait;

    const CODE_NOT_STARTED = 'label.not_started';
    const CODE_IN_PROGRESS = 'label.in_progress';
    const CODE_PENDING = 'label.pending';
    const CODE_OPEN = 'label.open';
    const CODE_CLOSED = 'label.closed';

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
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     */
    protected $sequence;

    /**
     * @var string
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    protected $code;

    /**
     * ProjectStatus constructor.
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
     * @return ProjectStatus
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
     * Get code.
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
