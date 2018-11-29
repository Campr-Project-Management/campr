<?php

namespace Component\Avatar;

use Component\Avatar\Model\AvatarAwareInterface;
use Component\Avatar\Model\GravatarAwareInterface;

class AvatarUrlResolver implements AvatarUrlResolverInterface
{
    /**
     * @var string
     */
    private $gravatarBaseUrl;

    /**
     * AvatarUrlResolver constructor.
     *
     * @param string $gravatarBaseUrl
     */
    public function __construct(string $gravatarBaseUrl)
    {
        $this->gravatarBaseUrl = $gravatarBaseUrl;
    }

    /**
     * @param AvatarAwareInterface $object
     *
     * @return string
     */
    public function resolve(AvatarAwareInterface $object): string
    {
        if ($object->getAvatarUrl()) {
            return $object->getAvatarUrl();
        }

        if (!($object instanceof GravatarAwareInterface)) {
            return '';
        }

        return $this->getGravatar($object);
    }

    /**
     * @param GravatarAwareInterface $object
     *
     * @return string
     */
    private function getGravatar(GravatarAwareInterface $object): string
    {
        $email = md5(strtolower(trim($object->getEmail())));

        return sprintf('%s/%s?d=identicon', $this->gravatarBaseUrl, $email);
    }
}
