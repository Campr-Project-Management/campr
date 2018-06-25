<?php

namespace AppBundle\Entity;

use Component\Resource\Model\CodeAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="todo_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TodoStatusRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @UniqueEntity(fields="code", message="unique.code")
 */
class TodoStatus implements CodeAwareInterface
{
    const CODE_DISCONTINUED = 'discontinued';

    const CODE_FINISHED = 'finished';

    const CODE_INITIATED = 'initiated';

    const CODE_ONHOLD = 'onhold';

    const CODE_ONGOING = 'ongoing';

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
     * @ORM\Column(name="code", type="string", length=255, nullable=false, unique=true)
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
     * @return TodoStatus
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

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return self::CODE_FINISHED === $this->code;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return !in_array($this->code, [self::CODE_FINISHED, self::CODE_DISCONTINUED]);
    }
}
