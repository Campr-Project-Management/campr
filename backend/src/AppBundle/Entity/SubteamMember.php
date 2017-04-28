<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="subteam_member")
 */
class SubteamMember
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="subteamMembers", cascade={"persist"})
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     * @Assert\NotBlank()
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var Subteam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subteam", inversedBy="subteamMembers")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subteam_id", referencedColumnName="id", nullable=false)
     * })
     * @Serializer\Exclude()
     */
    private $subteam;

    /**
     * @var SubteamRole[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SubteamRole", mappedBy="subteamMembers", cascade={"persist"})
     * @Serializer\Exclude()
     */
    private $subteamRoles;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subteamRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return SubteamMember
     */
    public function setUser(\AppBundle\Entity\User $user)
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

    /**
     * Set subteam.
     *
     * @param \AppBundle\Entity\Subteam $subteam
     *
     * @return SubteamMember
     */
    public function setSubteam(\AppBundle\Entity\Subteam $subteam)
    {
        $this->subteam = $subteam;

        return $this;
    }

    /**
     * Get subteam.
     *
     * @return \AppBundle\Entity\Subteam
     */
    public function getSubteam()
    {
        return $this->subteam;
    }

    /**
     * Add subteamRole.
     *
     * @param \AppBundle\Entity\SubteamRole $subteamRole
     *
     * @return SubteamMember
     */
    public function addSubteamRole(\AppBundle\Entity\SubteamRole $subteamRole)
    {
        $this->subteamRoles[] = $subteamRole;

        return $this;
    }

    /**
     * Remove subteamRole.
     *
     * @param \AppBundle\Entity\SubteamRole $subteamRole
     */
    public function removeSubteamRole(\AppBundle\Entity\SubteamRole $subteamRole)
    {
        $this->subteamRoles->removeElement($subteamRole);
    }

    /**
     * Get subteamRoles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubteamRoles()
    {
        return $this->subteamRoles;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     */
    public function getUserId()
    {
        return $this->user ? $this->user->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userName")
     */
    public function getUserName()
    {
        return $this->user ? (string) $this->user : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("subteam")
     */
    public function getSubteamId()
    {
        return $this->subteam ? $this->subteam->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("subteamName")
     */
    public function getSubteamName()
    {
        return $this->subteam ? (string) $this->subteam : null;
    }
}
