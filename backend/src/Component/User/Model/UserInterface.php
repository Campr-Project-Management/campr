<?php

namespace Component\User\Model;

use AppBundle\Entity\Company;
use Component\Avatar\Model\AvatarAwareInterface;
use Component\Avatar\Model\GravatarAwareInterface;
use Component\Resource\Model\EmailAwareInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\ToggleableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\TrustedComputerInterface;

interface UserInterface extends AdvancedUserInterface, ResourceInterface, \Serializable, TwoFactorInterface, TrustedComputerInterface, TimestampableInterface, ToggleableInterface, AvatarAwareInterface, GravatarAwareInterface, EmailAwareInterface
{
    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername($username);

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Set email.
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName();

    /**
     * @return string
     */
    public function getFullName();

    /**
     * Set password.
     *
     * @param string $password
     */
    public function setPassword($password);

    /**
     * Set plainPassword.
     *
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword);

    /**
     * Get plainPassword.
     *
     * @return string
     */
    public function getPlainPassword();

    /**
     * Set salt.
     *
     * @param string $salt
     */
    public function setSalt($salt);

    /**
     * Set roles.
     *
     * @param array $roles
     */
    public function setRoles(array $roles);

    /**
     * @return array
     */
    public function getRoles();

    /**
     * Has role.
     *
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role);

    /**
     * Get activationToken.
     *
     * @return string
     */
    public function getActivationToken();

    /**
     * Get activationTokenCreatedAt.
     *
     * @return \DateTime
     */
    public function getActivationTokenCreatedAt();

    /**
     * Get resetPasswordToken.
     *
     * @return string
     */
    public function getResetPasswordToken();

    /**
     * Get resetPasswordTokenCreatedAt.
     *
     * @return \DateTime
     */
    public function getResetPasswordTokenCreatedAt();

    /**
     * Set activatedAt.
     *
     * @param \DateTime $activatedAt
     */
    public function setActivatedAt(\DateTime $activatedAt = null);

    /**
     * Get activatedAt.
     *
     * @return \DateTime
     */
    public function getActivatedAt();

    /**
     * Set apiToken.
     *
     * @param string $apiToken
     */
    public function setApiToken($apiToken);

    /**
     * Get apiToken.
     *
     * @return string
     */
    public function getApiToken();

    /**
     * @return string
     */
    public function getFacebook();

    /**
     * @return string
     */
    public function getTwitter();

    /**
     * @return string
     */
    public function getInstagram();

    /**
     * @return string
     */
    public function getGplus();

    /**
     * @return string
     */
    public function getLinkedIn();

    /**
     * @return string
     */
    public function getMedium();

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $locale
     */
    public function setLocale(string $locale);

    /**
     * Get company.
     *
     * @return Company
     */
    public function getCompany();

    /**
     * @return string
     */
    public function getUuid();

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid);

    /**
     * @return bool
     */
    public function isSuspended();

    /**
     * @param bool $suspended
     */
    public function setSuspended(bool $suspended);

    /**
     * Get trustedComputers.
     *
     * @return array
     */
    public function getTrustedComputers();

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt();

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt = null);

    /**
     * @return bool
     */
    public function isDeleted();

    /**
     * Get theme.
     *
     * @return string
     */
    public function getTheme();

    /**
     * Set theme.
     *
     * @param string|null $theme
     */
    public function setTheme(string $theme = null);
}
