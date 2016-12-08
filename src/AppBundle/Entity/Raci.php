<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * Raci.
 *
 * @ORM\Table(name="raci")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RaciRepository")
 */
class Raci
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
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumn(name="work_package_id")
     */
    private $workPackage;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id")
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
     * @return Raci
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
     * @return Raci
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
     *
     * @return string
     */
    public function getWorkPackageName()
    {
        return $this->workPackage ? $this->workPackage->getName() : '-';
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Raci
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns user username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user ? $this->user->getUsername() : '-';
    }
}
