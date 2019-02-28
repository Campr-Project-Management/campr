<?php

namespace AppBundle\Entity;

use Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RiskStatusRepository")
 * @ORM\Table(name="risk_status")
 * @Cloner\Exclude()
 */
class RiskStatus implements ResourceInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     * @Serializer\Expose()
     */
    private $name;

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     *
     * @return RiskStatus
     */
    public function setName($name): RiskStatus
    {
        $this->name = $name;

        return $this;
    }
}
