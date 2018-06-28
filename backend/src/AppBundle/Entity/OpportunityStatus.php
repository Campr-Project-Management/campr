<?php

namespace AppBundle\Entity;

use Component\Resource\Model\CodeAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * OpportunityStatus.
 *
 * @ORM\Table(name="opportunity_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OpportunityStatusRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @UniqueEntity(fields="code", message="unique.code")
 */
class OpportunityStatus implements CodeAwareInterface
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

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
     * @return OpportunityStatus
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
