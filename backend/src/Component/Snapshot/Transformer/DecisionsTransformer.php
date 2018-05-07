<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Project;
use AppBundle\Entity\Todo;
use Doctrine\Common\Collections\ArrayCollection;

class DecisionsTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * TodosTransformer constructor.
     *
     * @param TransformerInterface $dateTransformer
     */
    public function __construct(TransformerInterface $dateTransformer)
    {
        $this->dateTransformer = $dateTransformer;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        return [
            'items' => $this->getItems($project->getDecisions()),
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
     * @param Decision[]|ArrayCollection $todos
     *
     * @return array
     */
    private function getItems($decisions): array
    {
        $items = [];

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
            'description' => $decision->getDescription(),
            'dueDate' => $this->dateTransformer->transform($decision->getDueDate()),
            'categoryId' => $decision->getDecisionCategoryId(),
            'categoryName' => $decision->getDecisionCategoryName(),
            'responsibilityId' => $decision->getResponsibilityId(),
            'responsibilityFullName' => $decision->getResponsibilityFullName(),
        ];
    }
}
