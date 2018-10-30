<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Media;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Serializer\Normalizer\JsonSerializableNormalizer;
use AppBundle\Services\FileSystemResolver;
use Component\Repository\RepositoryInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;
use Oneup\UploaderBundle\UploadEvents;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class MediaUploaderListener implements EventSubscriberInterface
{
    const TYPE = 'media';

    /**
     * @var FileSystemResolver
     */
    private $fileSystemResolver;

    /**
     * @var RepositoryInterface
     */
    private $projectRepository;

    /**
     * @var RepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var JsonSerializableNormalizer
     */
    private $jsonSerializerNormalizer;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MediaUploaderListener constructor.
     *
     * @param FileSystemResolver         $fileSystemResolver
     * @param RepositoryInterface        $projectRepository
     * @param RepositoryInterface        $mediaRepository
     * @param JsonSerializableNormalizer $jsonSerializerNormalizer
     * @param TokenStorageInterface      $tokenStorage
     * @param LoggerInterface            $logger
     */
    public function __construct(
        FileSystemResolver $fileSystemResolver,
        RepositoryInterface $projectRepository,
        RepositoryInterface $mediaRepository,
        JsonSerializableNormalizer $jsonSerializerNormalizer,
        TokenStorageInterface $tokenStorage,
        LoggerInterface $logger
    ) {
        $this->fileSystemResolver = $fileSystemResolver;
        $this->projectRepository = $projectRepository;
        $this->mediaRepository = $mediaRepository;
        $this->jsonSerializerNormalizer = $jsonSerializerNormalizer;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UploadEvents::POST_PERSIST => 'onPostPersist',
        ];
    }

    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        if (self::TYPE !== $event->getType()) {
            return;
        }

        /** @var File $file */
        $file = $event->getFile();
        $request = $event->getRequest();

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        try {
            $projectId = (int) $request->get('id');
            $project = $this->findProject($projectId);

            $media = $this->createMedia($file, $project);
            $originalName = $uploadedFile->getClientOriginalName();
            $media->setOriginalName($originalName);
            $media->setPath($originalName);

            $this->mediaRepository->add($media);

            /** @var AbstractResponse $response */
            $response = $event->getResponse();

            $media = $this->jsonSerializerNormalizer->normalize($media);
            foreach ($media as $key => $value) {
                $response[$key] = $value;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        } finally {
            @unlink($file->getRealPath());
        }
    }

    /**
     * @param File    $file
     * @param Project $project
     *
     * @return Media
     */
    private function createMedia(File $file, Project $project): Media
    {
        $fs = $this->getFileSystem($project);

        $media = new Media();
        $media->setFile($file);
        $media->setFileSize($file->getSize());
        $media->setMimeType($file->getMimeType());
        $media->setFileSystem($fs);
        $media->setUser($this->getUser());
        $media->makeAsTemporary();

        return $media;
    }

    /**
     * @param Project $project
     *
     * @return FileSystem
     */
    private function getFileSystem(Project $project): FileSystem
    {
        /** @var FileSystem $fs */
        $fs = $this->fileSystemResolver->resolve($project);

        if ($fs) {
            return $fs;
        }

        throw new \Exception('Filesystem is missing. Please contact us.');
    }

    /**
     * @param int $id
     *
     * @return Project
     */
    private function findProject(int $id): Project
    {
        /** @var Project $project */
        $project = $this->projectRepository->find($id);
        Assert::notNull($project, sprintf('Project with ID "%d" not found', $id));

        return $project;
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }

        $user = $token->getUser();
        if (!($user instanceof User)) {
            return null;
        }

        return $user;
    }
}
