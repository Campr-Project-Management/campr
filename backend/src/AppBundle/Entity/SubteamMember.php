<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\User\Model\UserAwareInterface;
use Component\User\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubteamMemberRepository")
 * @ORM\Table(name="subteam_member")
 */
class SubteamMember implements UserAwareInterface, ResourceInterface, CloneableInterface
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
     *     @ORM\JoinColumn(name="user_id", nullable=false, onDelete="CASCADE")
     * })
     * @Assert\NotBlank(message="not_blank.subteam_member.user")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var Subteam
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subteam", inversedBy="subteamMembers")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subteam_id", nullable=false, onDelete="CASCADE")
     * })
     * @Assert\NotBlank(message="not_blank.subteam_member.subteam")
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
     * @ORM\Column(name="lead", type="boolean", options={"default": false})
     */
    private $lead = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subteamRoles = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->user->getFullName();
    }

    /**
     * Set isLead.
     *
     * @param bool $lead
     *
     * @return SubteamMember
     */
    public function setLead(bool $lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLead()
    {
        return $this->lead;
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
     * @param UserInterface $user
     *
     * @return SubteamMember
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
     * Set subteam.
     *
     * @param Subteam $subteam
     *
     * @return SubteamMember
     */
    public function setSubteam(Subteam $subteam = null)
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
     * @return array
     */
    public function getSubteamRoleNames()
    {
        $roleNames = [];
        foreach ($this->getSubteamRoles() as $subteamRole) {
            $roleNames[] = $subteamRole->getName();
        }

        return $roleNames;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userDeleted")
     */
    public function isUserDeleted()
    {
        return $this->getUser()->isDeleted();
    }
}
