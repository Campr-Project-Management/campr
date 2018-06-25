<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Info;
use AppBundle\Entity\Project;
use AppBundle\Repository\InfoRepository;

class InfosTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var InfoRepository
     */
    private $infoRepository;

    /**
     * TodosTransformer constructor.
     *
     * @param TransformerInterface $dateTransformer
     * @param InfoRepository       $infoRepository
     */
    public function __construct(TransformerInterface $dateTransformer, InfoRepository $infoRepository)
    {
        $this->dateTransformer = $dateTransformer;
        $this->infoRepository = $infoRepository;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        return [
            'items' => $this->getItems($project),
        ];
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return $object instanceof Project;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getItems(Project $project): array
    {
        $items = [];
        $decisions = $this->infoRepository->getAllForStatusReport($project);

        foreach ($decisions as $decision) {
            $items[] = $this->getItem($decision);
        }

        return $items;
    }

    /**
     * @param Info $info
     *
     * @return array
     */
    private function getItem(Info $info): array
    {
        return [
            'id' => $info->getId(),
            'topic' => $info->getTopic(),
            'description' => $info->getDescription(),
            'expiresAt' => $this->dateTransformer->transform($info->getExpiresAt()),
            'isExpired' => $info->isExpired(),
            'categoryId' => $info->getInfoCategoryId(),
            'categoryName' => $info->getInfoCategoryName(),
            'meetingId' => $info->getMeetingId(),
            'meetingName' => $info->getMeetingName(),
            'responsibilityId' => $info->getResponsibilityId(),
            'responsibilityFullName' => $info->getResponsibilityFullName(),
        ];
    }
}
