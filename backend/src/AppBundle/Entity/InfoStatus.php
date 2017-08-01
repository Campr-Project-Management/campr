<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * InfoStatus.
 *
 * @ORM\Table(name="info_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InfoStatusRepository")
 */
class InfoStatus
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="not_blank.name")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7, options={"default"="#ffffff"})
     * @Assert\NotBlank(message="not_blank.color")
     * @Assert\Length(min="7", max="7")
     * @Assert\Regex(pattern="/^\#[a-f0-9]{6}$/i", message="format.color")
     */
    private $color = '#ffffff';

    /**
     * @return string
     */
    public function __toString(): string
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
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color)
    {
        $this->color = strtolower($color);
    }
}
