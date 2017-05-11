<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubteamRoleRepository")
 * @ORM\Table(name="subteam_role")
 */
class SubteamRole
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="not_blank.subteam_role.name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection|SubteamMember[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SubteamMember", inversedBy="subteamRoles", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinTable(
     *     name="subteam_role_subteam_member",
     *     joinColumns={
     *         @ORM\JoinColumn(name="subteam_role_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="subteam_member_id", referencedColumnName="id")
     *     }
     * )
     */
    private $subteamMembers;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subteamMembers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return SubteamRole
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
     * Set description.
     *
     * @param string $description
     *
     * @return SubteamRole
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add subteamMember.
     *
     * @param SubteamMember $subteamMember
     *
     * @return SubteamRole
     */
    public function addSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers[] = $subteamMember;

        return $this;
    }

    /**
     * Remove subteamMember.
     *
     * @param SubteamMember $subteamMember
     */
    public function removeSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers->removeElement($subteamMember);
    }

    /**
     * Get subteamMembers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubteamMembers()
    {
        return $this->subteamMembers;
    }
}
