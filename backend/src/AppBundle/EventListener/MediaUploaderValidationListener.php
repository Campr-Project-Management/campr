<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use Component\Repository\RepositoryInterface;
use Oneup\UploaderBundle\Event\ValidationEvent;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;
use Oneup\UploaderBundle\UploadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

class MediaUploaderValidationListener implements EventSubscriberInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RepositoryInterface
     */
    private $projectRepository;

    /**
     * MediaUploaderValidationListener constructor.
     *
     * @param ValidatorInterface  $validator
     * @param RepositoryInterface $projectRepository
     */
    public function __construct(ValidatorInterface $validator, RepositoryInterface $projectRepository)
    {
        $this->validator = $validator;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UploadEvents::VALIDATION => 'onValidation',
        ];
    }

    /**
     * @param ValidationEvent $event
     */
    public function onValidation(ValidationEvent $event)
    {
        if (MediaUploaderListener::TYPE !== $event->getType()) {
            return;
        }

        $request = $event->getRequest();
        $projectId = (int) $request->get('id');
        $project = $this->findProject($projectId);

        $violations = $this->validator->validate(
            $event->getFile(),
            [
                new File(
                    [
                        'maxSize' => sprintf('%dM', $this->getMaxUploadSize($project)),
                        'binaryFormat' => false,
                        'mimeTypes' => ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'],
                    ]
                ),
            ]
        );

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            throw new ValidationException($violation->getMessage());
        }
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    private function getMaxUploadSize(Project $project): float
    {
        return $project->getMaxUploadFileSize() / (1024 * 1024);
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
}
