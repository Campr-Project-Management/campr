<?php

namespace AppBundle\Entity;

use Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * InfoCategory.
 *
 * @ORM\Table(name="info_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InfoCategoryRepository")
 * @Cloner\Exclude()
 */
class InfoCategory implements ResourceInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="not_blank.name")
     * @Serializer\Expose()
     */
    private $name;

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
}
