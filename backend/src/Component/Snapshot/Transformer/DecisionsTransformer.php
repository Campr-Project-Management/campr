<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Project;
use AppBundle\Repository\DecisionRepository;

class DecisionsTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var DecisionRepository
     */
    private $decisionRepository;

    /**
     * TodosTransformer constructor.
     *
     * @param TransformerInterface $dateTransformer
     * @param DecisionRepository   $decisionRepository
     */
    public function __construct(TransformerInterface $dateTransformer, DecisionRepository $decisionRepository)
    {
        $this->dateTransformer = $dateTransformer;
        $this->decisionRepository = $decisionRepository;
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
        $decisions = $this->decisionRepository->getAllForStatusReport($project);

        foreach ($decisions as $decision) {
            $items[] = $this->getItem($decision);
        }

        return $items;
    }

    /**
     * @param Decision $decision
     *
     * @return array
     */
    private function getItem(Decision $decision): array
    {
        return [
            'id' => $decision->getId(),
            'title' => $decision->getTitle(),
            'meetingId' => $decision->getMeetingId(),
            'meetingName' => $decision->getMeetingName(),
            'isDone' => $decision->isDone(),
            'description' => $decision->getDescription(),
            'dueDate' => $this->dateTransformer->transform($decision->getDueDate()),
            'createdAt' => $this->dateTransformer->transform($decision->getCreatedAt()),
            'categoryId' => $decision->getDecisionCategoryId(),
            'categoryName' => $decision->getDecisionCategoryName(),
            'responsibilityId' => $decision->getResponsibilityId(),
            'responsibilityFullName' => $decision->getResponsibilityFullName(),
        ];
    }
}
