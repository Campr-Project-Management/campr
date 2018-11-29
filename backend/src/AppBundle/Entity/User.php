<?php

namespace AppBundle\Entity;

use Component\Resource\Model\TimestampableTrait;
use Component\Resource\Model\ToggleableTrait;
use Component\User\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User.
 *
 * @ORM\Table(name="user", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="username_unique", columns={"username"}),
 *     @ORM\UniqueConstraint(name="email_unique", columns={"email"})
 * })
 * @UniqueEntity("uuid")
 * @UniqueEntity(
 *      fields="email",
 *      errorPath="email",
 *      message="unique.email",
 *      groups={"", "Default", "HomepageSignUp"}
 * )
 * @UniqueEntity(
 *      fields="username",
 *      errorPath="username",
 *      message="unique.username",
 *      groups={"", "Default", "HomepageSignUp"}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")\
 * @Vich\Uploadable
 */
class User implements UserInterface
{
    use TimestampableTrait, ToggleableTrait;

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const GRAVATAR_BASE_URL = 'https://www.gravatar.com/avatar/';
    const THEME_DARK = 'dark';
    const THEME_LIGHT = 'light';

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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="not_blank.username")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=false)
     * @Assert\Email(message="invalid.email", groups={"HomepageSignUp"})
     * @Assert\NotBlank(message="not_blank.email", groups={"HomepageSignUp"})
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=128, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="invalid.full_name", groups={"HomepageSignUp"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=128, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     */
    private $roles;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false, options={"default"=0})
     */
    protected $enabled = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="suspended", type="boolean", nullable=false, options={"default"=0})
     */
    private $suspended = false;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="activation_token", type="string", length=32, nullable=true)
     */
    private $activationToken;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="activation_token_created_at", type="datetime", nullable=true)
     */
    private $activationTokenCreatedAt;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="reset_password_token", type="string", length=32, nullable=true)
     */
    private $resetPasswordToken;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="reset_password_token_created_at", type="datetime", nullable=true)
     */
    private $resetPasswordTokenCreatedAt;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="activated_at", type="datetime", nullable=true)
     */
    private $activatedAt;

    /**
     * @var ArrayCollection|Media[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="user")
     */
    private $medias;

    /**
     * @var Team[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Team", mappedBy="user")
     */
    private $teams;

    /**
     * @var TeamMember[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TeamMember", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    private $teamMembers;

    /**
     * @var string
     *
     * @ORM\Column(name="api_token", type="string", length=255, unique=true)
     */
    private $apiToken;

    /**
     * @var ArrayCollection|TeamInvite[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TeamInvite", mappedBy="user")
     */
    private $teamInvites;

    /**
     * @var string
     *
     * @ORM\Column(name="widget_settings", type="json_array", nullable=false)
     */
    private $widgetSettings;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=256, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=256, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=256, nullable=true)
     */
    private $instagram;

    /**
     * @var string
     *
     * @ORM\Column(name="gplus", type="string", length=256, nullable=true)
     */
    private $gplus;

    /**
     * @var string
     *
     * @ORM\Column(name="linked_in", type="string", length=256, nullable=true)
     */
    private $linkedIn;

    /**
     * @var string
     *
     * @ORM\Column(name="medium", type="string", length=256, nullable=true)
     */
    private $medium;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="createdBy")
     */
    private $ownedDistributionLists;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="users")
     */
    private $distributionLists;

    /**
     * @var ArrayCollection|Contract[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="createdBy")
     */
    private $contracts;

    /**
     * @var ArrayCollection|Meeting[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="createdBy")
     */
    private $ownedMeetings;

    /**
     * @var ArrayCollection|Project[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Project", mappedBy="userFavorites")
     */
    private $favoriteProjects;

    /**
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="google_auth_secret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="trusted_computers", type="json_array")
     */
    private $trustedComputers;

    /**
     * @var SubteamMember[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SubteamMember", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    private $subteamMembers;

    /**
     * @var ProjectUser[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectUser", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    private $projectUsers;

    /**
     * @var StatusReport[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\StatusReport", mappedBy="createdBy")
     */
    private $statusReports;

    /**
     * @var MeetingReport[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingReport", mappedBy="createdBy")
     */
    private $meetingReports;

    /**
     * @var array
     * @ORM\Column(name="sign_up_details", type="json_array")
     */
    private $signUpDetails = [];

    /**
     * @var string
     * @ORM\Column(name="locale", type="string", length=8, nullable=false, options={"default":"en"})
     */
    private $locale = 'en';

    /**
     * @var Company
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="company_id", onDelete="SET NULL")
     * })
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_url", type="string", nullable=true)
     */
    private $avatarUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=20, options={"default": "dark"})
     */
    private $theme;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->salt = md5(uniqid('', true));
        $this->roles = [];
        $this->widgetSettings = [];
        $this->trustedComputers = [];
        $this->createdAt = new \DateTime();
        $this->medias = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->teamMembers = new ArrayCollection();
        $this->apiToken = hash('sha512', microtime(true).uniqid('campr', true));
        $this->teamInvites = new ArrayCollection();
        $this->ownedDistributionLists = new ArrayCollection();
        $this->distributionLists = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->ownedMeetings = new ArrayCollection();
        $this->favoriteProjects = new ArrayCollection();
        $this->subteamMembers = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();
        $this->statusReports = new ArrayCollection();
        $this->meetingReports = new ArrayCollection();
        $this->theme = User::THEME_DARK;
    }

    public function __toString()
    {
        return $this->getFullName();
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
     * Set widgetSettings.
     *
     * @param array $widgetSettings
     */
    public function setWidgetSettings(array $widgetSettings)
    {
        $this->widgetSettings = $widgetSettings;
    }

    public function getWidgetSettings()
    {
        $widgetSettings = $this->widgetSettings;

        if (!is_array($widgetSettings)) {
            $widgetSettings = [];
        }

        return $widgetSettings;
    }

    /**
     * Has widgetSetting.
     *
     * @param $widgetSetting
     *
     * @return bool
     */
    public function hasWidgetSetting($widgetSetting)
    {
        return in_array($widgetSetting, $this->getWidgetSettings());
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username ?? $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        if (empty($this->username)) {
            $this->username = $email;
        }

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function setFullName($fullName)
    {
        $parts = explode(' ', $fullName);

        switch (true) {
            case count($parts) >= 2:
                $this->lastName = array_pop($parts);
                $this->firstName = implode(' ', $parts);
                break;
            case 1 == count($parts):
                $this->lastName = $fullName;
                break;
        }
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s', $this->firstName, $this->lastName));
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set plainPassword.
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set salt.
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        if (!is_array($roles)) {
            $roles = [];
        }

        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * Returns roles names.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("roles")
     *
     * @return string
     */
    public function getRolesNames()
    {
        return implode(', ', $this->roles);
    }

    /**
     * Has role.
     *
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * return the admin status.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isAdmin")
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN) || $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    /**
     * Set isEnabled.
     *
     * @param bool $enabled
     *
     * @return User
     */
    public function setIsEnabled($enabled)
    {
        $this->setEnabled($enabled);

        return $this;
    }

    /**
     * Get isEnabled.
     *
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->isEnabled();
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set isSuspended.
     *
     * @param bool $suspended
     */
    public function setIsSuspended($suspended)
    {
        $this->setSuspended($suspended);
    }

    /**
     * Get isSuspended.
     *
     * @return bool
     */
    public function getIsSuspended()
    {
        return $this->isSuspended();
    }

    /**
     * Set activationToken.
     *
     * @param string $activationToken
     */
    public function setActivationToken($activationToken)
    {
        $this->activationToken = $activationToken;
    }

    /**
     * Get activationToken.
     *
     * @return string
     */
    public function getActivationToken()
    {
        return $this->activationToken;
    }

    /**
     * Set activationTokenCreatedAt.
     *
     * @param \DateTime $activationTokenCreatedAt
     */
    public function setActivationTokenCreatedAt(\DateTime $activationTokenCreatedAt = null)
    {
        $this->activationTokenCreatedAt = $activationTokenCreatedAt;
    }

    /**
     * Get activationTokenCreatedAt.
     *
     * @return \DateTime
     */
    public function getActivationTokenCreatedAt()
    {
        return $this->activationTokenCreatedAt;
    }

    /**
     * Set resetPasswordToken.
     *
     * @param string $resetPasswordToken
     */
    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * Get resetPasswordToken.
     *
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    /**
     * Set resetPasswordTokenCreatedAt.
     *
     * @param \DateTime $resetPasswordTokenCreatedAt
     */
    public function setResetPasswordTokenCreatedAt(\DateTime $resetPasswordTokenCreatedAt = null)
    {
        $this->resetPasswordTokenCreatedAt = $resetPasswordTokenCreatedAt;
    }

    /**
     * Get resetPasswordTokenCreatedAt.
     *
     * @return \DateTime
     */
    public function getResetPasswordTokenCreatedAt()
    {
        return $this->resetPasswordTokenCreatedAt;
    }

    /**
     * Serialize id, email and password.
     *
     * @return mixed
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->email,
                $this->password,
            ]
        );
    }

    /**
     * Unserialize string.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     *  Sets the plain password to null.
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword(null);
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Set activatedAt.
     *
     * @param \DateTime $activatedAt
     */
    public function setActivatedAt(\DateTime $activatedAt = null)
    {
        $this->activatedAt = $activatedAt;
    }

    /**
     * Get activatedAt.
     *
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * Add media.
     *
     * @param Media $media
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;
    }

    /**
     * Remove media.
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias.
     *
     * @return ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add teamMember.
     *
     * @param TeamMember $teamMember
     */
    public function addTeamMember(TeamMember $teamMember)
    {
        $this->teamMembers[] = $teamMember;
        $teamMember->setUser($this);
    }

    /**
     * Remove teamMember.
     *
     * @param TeamMember $teamMember
     */
    public function removeTeamMember(TeamMember $teamMember)
    {
        $this->teamMembers->removeElement($teamMember);
        $teamMember->setUser(null);
    }

    /**
     * Get teamMembers.
     *
     * @return TeamMember[]|ArrayCollection
     */
    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    public function hasTeam(Team $team)
    {
        foreach ($this->teamMembers as $teamMember) {
            if ($teamMember->getTeam() === $team) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add team.
     *
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this->teams[] = $team;
    }

    /**
     * Remove team.
     *
     * @param Team $team
     */
    public function removeTeam(Team $team)
    {
        $this->teams->removeElement($team);
    }

    /**
     * Get teams.
     *
     * @return Team[]|ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set apiToken.
     *
     * @param string $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Get apiToken.
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Add teamInvite.
     *
     * @param TeamInvite $teamInvite
     */
    public function addTeamInvite(TeamInvite $teamInvite)
    {
        $this->teamInvites[] = $teamInvite;
    }

    /**
     * Remove teamInvite.
     *
     * @param TeamInvite $teamInvite
     */
    public function removeTeamInvite(TeamInvite $teamInvite)
    {
        $this->teamInvites->removeElement($teamInvite);
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
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return string
     */
    public function getGplus()
    {
        return $this->gplus;
    }

    /**
     * @param string $gplus
     */
    public function setGplus($gplus)
    {
        $this->gplus = $gplus;
    }

    /**
     * @return string
     */
    public function getLinkedIn()
    {
        return $this->linkedIn;
    }

    /**
     * @param string $linkedIn
     */
    public function setLinkedIn($linkedIn)
    {
        $this->linkedIn = $linkedIn;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param string $medium
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;
    }

    /**
     * Add ownedDistributionList.
     *
     * @param DistributionList $ownedDistributionList
     */
    public function addOwnedDistributionList(DistributionList $ownedDistributionList)
    {
        $this->ownedDistributionLists[] = $ownedDistributionList;
    }

    /**
     * Remove ownedDistributionList.
     *
     * @param DistributionList $ownedDistributionList
     */
    public function removeOwnedDistributionList(DistributionList $ownedDistributionList)
    {
        $this->ownedDistributionLists->removeElement($ownedDistributionList);
    }

    /**
     * Get ownedDistributionLists.
     *
     * @return ArrayCollection|DistributionList[]
     */
    public function getOwnedDistributionLists()
    {
        return $this->ownedDistributionLists;
    }

    /**
     * Add distributionList.
     *
     * @param DistributionList $distributionList
     */
    public function addDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists[] = $distributionList;
        $distributionList->addUser($this);
    }

    /**
     * Remove distributionList.
     *
     * @param DistributionList $distributionList
     */
    public function removeDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists->removeElement($distributionList);
        $distributionList->removeUser($this);
    }

    /**
     * Get distributionLists.
     *
     * @return ArrayCollection|DistributionList[]
     */
    public function getDistributionLists()
    {
        return $this->distributionLists;
    }

    /**
     * Add contract.
     *
     * @param Contract $contract
     */
    public function addContract(Contract $contract)
    {
        $this->contracts[] = $contract;
    }

    /**
     * @param Contract $contract
     */
    public function removeContract(Contract $contract)
    {
        $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return ArrayCollection|Contract[]
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Add ownedMeeting.
     *
     * @param Meeting $ownedMeeting
     */
    public function addOwnedMeeting(Meeting $ownedMeeting)
    {
        $this->ownedMeetings[] = $ownedMeeting;
    }

    /**
     * Remove ownedMeeting.
     *
     * @param Meeting $ownedMeeting
     */
    public function removeOwnedMeeting(Meeting $ownedMeeting)
    {
        $this->ownedMeetings->removeElement($ownedMeeting);
    }

    /**
     * Get ownedMeetings.
     *
     * @return ArrayCollection|Meeting[]
     */
    public function getOwnedMeetings()
    {
        return $this->ownedMeetings;
    }

    /**
     * Add favoriteProject.
     *
     * @param Project $favoriteProject
     */
    public function addFavoriteProject(Project $favoriteProject)
    {
        $this->favoriteProjects[] = $favoriteProject;
    }

    /**
     * Remove favoriteProject.
     *
     * @param Project $favoriteProject
     */
    public function removeFavoriteProject(Project $favoriteProject)
    {
        $this->favoriteProjects->removeElement($favoriteProject);
    }

    /**
     * Get favoriteProjects.
     *
     * @return ArrayCollection
     */
    public function getFavoriteProjects()
    {
        return $this->favoriteProjects;
    }

    /**
     * Get google auth secret.
     *
     * @return string
     */
    public function getGoogleAuthenticatorSecret()
    {
        return $this->googleAuthenticatorSecret;
    }

    /**
     * Set google auth secret.
     *
     * @param int $googleAuthenticatorSecret
     */
    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret)
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * Add new trusted computer.
     *
     * @param           $token
     * @param \DateTime $validUntil
     */
    public function addTrustedComputer($token, \DateTime $validUntil)
    {
        $this->trustedComputers[$token] = $validUntil->format('r');
    }

    /**
     * Check if is a trusted computer.
     *
     * @param $token
     *
     * @return bool
     */
    public function isTrustedComputer($token)
    {
        if (isset($this->trustedComputers[$token])) {
            $now = new \DateTime();
            $validUntil = new \DateTime($this->trustedComputers[$token]);

            return $now < $validUntil;
        }

        return false;
    }

    /**
     * Get gravatar url.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("gravatar")
     *
     * @return string
     */
    public function getGravatar()
    {
        $email = md5(strtolower(trim($this->getEmail())));
        $gravatarUrl = sprintf('%s%s?d=identicon', self::GRAVATAR_BASE_URL, $email);

        return $gravatarUrl;
    }

    /**
     * @param SubteamMember $subteamMember
     */
    public function addSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers[] = $subteamMember;
        $subteamMember->setUser($this);
    }

    /**
     * @param SubteamMember $subteamMember
     */
    public function removeSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers->removeElement($subteamMember);
    }

    /**
     * @return Collection|SubteamMember[]
     */
    public function getSubteamMembers()
    {
        return $this->subteamMembers;
    }

    /**
     * @param ProjectUser $projectUser
     */
    public function addProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;
        $projectUser->setUser($this);
    }

    /**
     * @param ProjectUser $projectUser
     */
    public function removeProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers->removeElement($projectUser);
        $projectUser->setUser(null);
    }

    /**
     * @return ProjectUser[]|ArrayCollection
     */
    public function getProjectUsers()
    {
        return $this->projectUsers;
    }

    /**
     * @param Project $project
     *
     * @return ProjectUser
     */
    public function getProjectUser(Project $project)
    {
        return $this
            ->getProjectUsers()
            ->filter(
                function (ProjectUser $projectUser) use ($project) {
                    return $projectUser->getProject()->getId() === $project->getId();
                }
            )
            ->first()
            ;
    }

    public function addSubteam(Subteam $subteam)
    {
        foreach ($this->subteamMembers as $subteamMember) {
            if ($subteamMember->getSubteam() === $subteam) {
                return;
            }
        }

        $subteamMember = new SubteamMember();
        $subteamMember->setSubteam($subteam);
        $this->addSubteamMember($subteamMember);
    }

    public function removeSubteam(Subteam $subteam)
    {
        foreach ($this->subteamMembers as $subteamMember) {
            if ($subteamMember->getSubteam() === $subteam) {
                $this->removeSubteamMember($subteamMember);
            }
        }
    }

    /**
     * @return Collection
     */
    public function getSubteams()
    {
        return $this->subteamMembers->map(
            function (SubteamMember $subteamMember) {
                return $subteamMember->getSubteam();
            }
        );
    }

    /**
     * @param StatusReport $statusReport
     */
    public function addStatusReport(StatusReport $statusReport)
    {
        $this->statusReports[] = $statusReport;
    }

    /**
     * @param StatusReport $statusReport
     */
    public function removeStatusReport(StatusReport $statusReport)
    {
        $this->statusReports->removeElement($statusReport);
    }

    /**
     * @return StatusReport[]|ArrayCollection
     */
    public function getStatusReports()
    {
        return $this->statusReports;
    }

    /**
     * @return array
     */
    public function getSignUpDetails()
    {
        return $this->signUpDetails;
    }

    /**
     * @param array $signUpDetails
     */
    public function setSignUpDetails($signUpDetails)
    {
        $this->signUpDetails = $signUpDetails;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set company.
     *
     * @param Company $company
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;
    }

    /**
     * Get company.
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Returns company name.
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company ? $this->company->getName() : null;
    }

    /**
     * @return string
     */
    public function getUuid()
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
     * @return bool
     */
    public function isSuspended()
    {
        return $this->suspended;
    }

    /**
     * @param bool $suspended
     */
    public function setSuspended(bool $suspended)
    {
        $this->suspended = $suspended;
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return (string) $this->avatarUrl;
    }

    /**
     * @param string $avatarUrl
     */
    public function setAvatarUrl(string $avatarUrl = null)
    {
        $this->avatarUrl = $avatarUrl;
    }

    /**
     * Set trustedComputers.
     *
     * @param array $trustedComputers
     */
    public function setTrustedComputers($trustedComputers)
    {
        $this->trustedComputers = $trustedComputers;
    }

    /**
     * Get trustedComputers.
     *
     * @return array
     */
    public function getTrustedComputers()
    {
        return $this->trustedComputers;
    }

    /**
     * Add meetingReport.
     *
     * @param MeetingReport $meetingReport
     */
    public function addMeetingReport(MeetingReport $meetingReport)
    {
        $this->meetingReports[] = $meetingReport;
    }

    /**
     * Remove meetingReport.
     *
     * @param MeetingReport $meetingReport
     */
    public function removeMeetingReport(MeetingReport $meetingReport)
    {
        $this->meetingReports->removeElement($meetingReport);
    }

    /**
     * Get meetingReports.
     *
     * @return Collection
     */
    public function getMeetingReports()
    {
        return $this->meetingReports;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("deleted")
     *
     * @return bool
     */
    public function isDeleted()
    {
        return (bool) $this->deletedAt;
    }

    /**
     * Get theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set theme.
     *
     * @param string|null $theme
     *
     * @return $this
     */
    public function setTheme(string $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
