<?php

namespace AppBundle\Entity;

use Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Impact.
 *
 * @ORM\Table(name="impact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImpactRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 */
class Impact implements ResourceInterface
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
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

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
     * @return Impact
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
     * @return Impact
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
