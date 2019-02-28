<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\User\Model\UserAwareInterface;
use Component\User\Model\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rasci.
 *
 * @ORM\Table(name="rasci")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RasciRepository")
 */
class Rasci implements UserAwareInterface, ResourceInterface, CloneableInterface
{
    const DATA_RESPONSIBLE = 'responsible';
    const DATA_ACCOUNTABLE = 'accountable';
    const DATA_SUPPORT = 'support';
    const DATA_CONSULTED = 'consulted';
    const DATA_INFORMED = 'informed';

    public static function getDataChoices()
    {
        return [
            '' => '',
            self::DATA_RESPONSIBLE => self::DATA_RESPONSIBLE,
            self::DATA_ACCOUNTABLE => self::DATA_ACCOUNTABLE,
            self::DATA_SUPPORT => self::DATA_SUPPORT,
            self::DATA_CONSULTED => self::DATA_CONSULTED,
            self::DATA_INFORMED => self::DATA_INFORMED,
        ];
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumn(name="work_package_id", onDelete="CASCADE")
     */
    private $workPackage;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", length=255)
     */
    private $data;

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
     * Set data.
     *
     * @param string $data
     *
     * @return Rasci
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set workpackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return Rasci
     */
    public function setWorkPackage(WorkPackage $workPackage = null)
    {
        $this->workPackage = $workPackage;

        return $this;
    }

    /**
     * Get workpackage.
     *
     * @return WorkPackage
     */
    public function getWorkPackage()
    {
        return $this->workPackage;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackage")
     */
    public function getWorkPackageId()
    {
        return $this->workPackage ? $this->workPackage->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageName")
     */
    public function getWorkPackageName()
    {
        return $this->workPackage ? $this->workPackage->getName() : null;
    }

    /**
     * Set user.
     *
     * @param UserInterface $user
     *
     * @return Rasci
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns user full name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFullName")
     *
     * @return string
     */
    public function getUserFullName()
    {
        return $this->user ? $this->user->getFullName() : null;
    }

    /**
     * @return bool
     */
    public function isResponsible(): bool
    {
        return self::DATA_RESPONSIBLE === $this->data;
    }

    /**
     * @return bool
     */
    public function isAccountable(): bool
    {
        return self::DATA_ACCOUNTABLE === $this->data;
    }

    /**
     * @return bool
     */
    public function isSupport(): bool
    {
        return self::DATA_SUPPORT === $this->data;
    }

    /**
     * @return bool
     */
    public function isConsulted(): bool
    {
        return self::DATA_CONSULTED === $this->data;
    }

    /**
     * @return bool
     */
    public function isInformed(): bool
    {
        return self::DATA_INFORMED === $this->data;
    }
}
