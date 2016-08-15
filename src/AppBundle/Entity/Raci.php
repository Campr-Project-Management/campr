<?php

namespace AppBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumn(name="work_package_id")
     */
    private $workpackage;

    /**
     * @var WorkPackage
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
     * @param \AppBundle\Entity\WorkPackage $workpackage
     *
     * @return Raci
     */
    public function setWorkpackage(\AppBundle\Entity\WorkPackage $workpackage = null)
    {
        $this->workpackage = $workpackage;

        return $this;
    }

    /**
     * Get workpackage.
     *
     * @return \AppBundle\Entity\WorkPackage
     */
    public function getWorkpackage()
    {
        return $this->workpackage;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Raci
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
