<?php

namespace AppBundle\Entity;

use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Component\User\Model\UserAwareInterface;
use Component\User\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * Team a.k.a. Workspace.
 *
 * @ORM\Table(name="team", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="slug_unique", columns={"slug"}),
 * }))
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamRepository")
 * @UniqueEntity(fields={"slug"}, message="unique.workspace.slug")
 * @UniqueEntity(fields={"uuid"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 * @Cloner\Exclude()
 */
class Team implements UserAwareInterface, TimestampableInterface, ResourceInterface, CloneableInterface
{
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="uuid", unique=true, nullable=true)
     * @Serializer\Exclude()
     */
    private $uuid;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="teams")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="user_id", onDelete="SET NULL")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default" = 1})
     */
    private $enabled = true;

    /**
     * @Vich\UploadableField(mapping="team_images", fileNameProperty="logo")
     * @Serializer\Exclude()
     *
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     *
     * @Serializer\Exclude()
     *
     * @var string
     */
    private $logo;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var ArrayCollection|TeamMember[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TeamMember", mappedBy="team", cascade={"all"})
     */
    private $teamMembers;

    /**
     * @var ArrayCollection|TeamInvite[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TeamInvite", mappedBy="team")
     */
    private $teamInvites;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_url", type="string", nullable=true)
     */
    private $logoUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="available", type="boolean", options={"default" = 0})
     */
    private $available = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->teamMembers = new ArrayCollection();
        $this->teamInvites = new ArrayCollection();
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function __toString()
    {
        return $this->name;
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
     * @param $id
     *
     * @return Team
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return Team
     */
    public function setUser(UserInterface $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getUserUsername(): string
    {
        return $this->user
            ? $this->user->getEmail()
            : ''
        ;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Team
     */
    public function setName($name): self
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Team
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Team
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * Set logoFile.
     *
     * @param File|null $image
     *
     * @return User
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get logoFile.
     *
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * Add teamMember.
     *
     * @param TeamMember $teamMember
     *
     * @return Team
     */
    public function addTeamMember(TeamMember $teamMember): self
    {
        $this->teamMembers[] = $teamMember;

        return $this;
    }

    /**
     * Remove teamMember.
     *
     * @param TeamMember $teamMember
     *
     * @return Team
     */
    public function removeTeamMember(TeamMember $teamMember): self
    {
        $this->teamMembers->removeElement($teamMember);

        return $this;
    }

    /**
     * Get teamMembers.
     *
     * @return ArrayCollection|TeamMember[]
     */
    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    /**
     * @param User $user
     *
     * @return TeamMember|null
     */
    public function getTeamMemberForUser(User $user)
    {
        foreach ($this->teamMembers as $teamMember) {
            if ($teamMember->getUser() === $user) {
                return $teamMember;
            }
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function userIsMember(User $user)
    {
        return $this
            ->teamMembers
            ->map(
                function (TeamMember $teamMember) {
                    return $teamMember->getUser();
                }
            )
            ->contains($user);
    }

    /**
     * Add teamInvite.
     *
     * @param TeamInvite $teamInvite
     *
     * @return Team
     */
    public function addTeamInvite(TeamInvite $teamInvite)
    {
        $this->teamInvites[] = $teamInvite;

        return $this;
    }

    /**
     * Remove teamInvite.
     *
     * @param TeamInvite $teamInvite
     *
     * @return Team
     */
    public function removeTeamInvite(TeamInvite $teamInvite)
    {
        $this->teamInvites->removeElement($teamInvite);

        return $this;
    }

    /**
     * Get teamInvites.
     *
     * @return ArrayCollection|TeamInvite[]
     */
    public function getTeamInvites()
    {
        return $this->teamInvites;
    }

    /**
     * Returns User id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user ? $this->user->getId() : null;
    }

    /**
     * Returns User name.
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
    public function isAvailable()
    {
        return $this->available;
    }

    /**
     * @param bool $available
     *
     * @return $this
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnvName(): string
    {
        return str_replace('-', '_', $this->getSlug());
    }

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
        foreach ($this->teamMembers as $teamMember) {
            $teamMember->setDeletedAt($deletedAt);
        }
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ? \DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getUuid(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getLogoUrl(): string
    {
        return (string) $this->logoUrl;
    }

    /**
     * @param string $logoUrl
     */
    public function setLogoUrl(string $logoUrl = null)
    {
        $this->logoUrl = $logoUrl;
    }
}
