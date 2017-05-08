<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubteamMemberRepository")
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
     * @var bool
     *
     * @ORM\Column(name="is_lead", type="boolean", options={"default": false})
     */
    private $isLead = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subteamRoles = new ArrayCollection();
    }

    /**
     * Set isLead.
     *
     * @param bool $isLead
     *
     * @return SubteamMember
     */
    public function setIsLead($isLead)
    {
        $this->isLead = $isLead;

        return $this;
    }

    /**
     * Get isLead.
     *
     * @return bool
     */
    public function getIsLead()
    {
        return $this->isLead;
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
     * @param User $user
     *
     * @return SubteamMember
     */
    public function setUser(User $user)
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
     * Set subteam.
     *
     * @param Subteam $subteam
     *
     * @return SubteamMember
     */
    public function setSubteam(Subteam $subteam)
    {
        $this->subteam = $subteam;

        return $this;
    }

    /**
     * Get subteam.
     *
     * @return Subteam
     */
    public function getSubteam()
    {
        return $this->subteam;
    }

    /**
     * Add subteamRole.
     *
     * @param SubteamRole $subteamRole
     *
     * @return SubteamMember
     */
    public function addSubteamRole(SubteamRole $subteamRole)
    {
        $this->subteamRoles[] = $subteamRole;

        return $this;
    }

    /**
     * Remove subteamRole.
     *
     * @param SubteamRole $subteamRole
     */
    public function removeSubteamRole(SubteamRole $subteamRole)
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
     * @Serializer\SerializedName("userFullName")
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

    /**
     * Returns user facebook.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFacebook")
     *
     * @return string
     */
    public function getUserFacebook()
    {
        return $this->user ? $this->user->getFacebook() : null;
    }

    /**
     * Returns user twitter.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userTwitter")
     *
     * @return string
     */
    public function getUserTwitter()
    {
        return $this->user ? $this->user->getTwitter() : null;
    }

    /**
     * Returns user linkedin.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userLinkedIn")
     *
     * @return string
     */
    public function getUserLinkedIn()
    {
        return $this->user ? $this->user->getLinkedIn() : null;
    }

    /**
     * Returns user gplus.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userGplus")
     *
     * @return string
     */
    public function getUserGplus()
    {
        return $this->user ? $this->user->getGplus() : null;
    }

    /**
     * Returns user email.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userEmail")
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user ? $this->user->getEmail() : null;
    }

    /**
     * Returns user phone.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userPhone")
     *
     * @return string
     */
    public function getUserPhone()
    {
        return $this->user ? $this->user->getPhone() : null;
    }

    /**
     * Returns subteam role names.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("subteamRoleNames")
     *
     * @return string
     */
    public function getSubteamRoleNames()
    {
        $roleNames = [];
        foreach ($this->getSubteamRoles() as $subteamRole) {
            $roleNames[] = $subteamRole->getName();
        }

        return $roleNames;
    }
}
