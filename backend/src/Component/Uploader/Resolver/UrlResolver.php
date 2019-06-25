<?php

namespace Component\Uploader\Resolver;

use Component\Resource\Model\ResourceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class UrlResolver implements UrlResolverInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * UserUploadedAvatarUrlResolver constructor.
     *
     * @param StorageInterface $storage
     * @param RouterInterface  $router
     * @param string           $fieldName
     */
    public function __construct(StorageInterface $storage, RouterInterface $router, string $fieldName)
    {
        $this->storage = $storage;
        $this->router = $router;
        $this->fieldName = $fieldName;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return string
     */
    public function resolve($resource): string
    {
        $path = (string) $this->storage->resolveUri($resource, $this->fieldName);
        $path = ltrim($path, DIRECTORY_SEPARATOR);

        if (empty($path)) {
            return $path;
        }

        return $this->router->generate(
            'app_uploader_url',
            ['path' => $path],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
