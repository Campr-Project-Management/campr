<?php

namespace AppBundle\Entity;

use Component\Currency\CurrencyInterface;
use Component\Model\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Intl\Intl;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 * @ORM\Table(name="currency")
 * @UniqueEntity(fields="code", message="unique.currency.code")
 */
class Currency implements CurrencyInterface
{
    use TimestampableTrait;

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=3, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3)
     */
    private $code;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="create")
     *
     * @Serializer\Exclude()
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @Serializer\Exclude()
     */
    protected $updatedAt;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @Serializer\VirtualProperty()
     *
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
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getName(): string
    {
        return (string) Intl::getCurrencyBundle()->getCurrencyName($this->getCode());
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return (string) Intl::getCurrencyBundle()->getCurrencySymbol($this->getCode());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode();
    }
}
