<?php

namespace AppBundle\Entity;

use Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * Risk Status.
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @Cloner\Exclude()
 */
class Status implements ResourceInterface
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
     * @return Status
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
}
