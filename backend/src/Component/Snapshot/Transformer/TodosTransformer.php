<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\Todo;
use Doctrine\Common\Collections\ArrayCollection;

class TodosTransformer extends AbstractTransformer
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
            'items' => $this->getItems($project->getTodos()),
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
     * @param Todo[]|ArrayCollection $todos
     *
     * @return array
     */
    private function getItems($todos): array
    {
        $items = [];

        foreach ($todos as $todo) {
            $items[] = $this->getItem($todo);
        }

        return $items;
    }

    /**
     * @param Todo $todo
     *
     * @return array
     */
    private function getItem(Todo $todo): array
    {
        return [
            'id' => $todo->getId(),
            'statusId' => $todo->getStatusId(),
            'statusName' => $todo->getStatusName(),
            'meetingId' => $todo->getMeetingId(),
            'meetingName' => $todo->getMeetingName(),
            'title' => $todo->getTitle(),
            'description' => $todo->getDescription(),
            'dueDate' => $this->dateTransformer->transform($todo->getDueDate()),
            'categoryId' => $todo->getTodoCategoryId(),
            'categoryName' => $todo->getTodoCategoryName(),
            'responsibilityId' => $todo->getResponsibilityId(),
            'responsibilityFullName' => $todo->getResponsibilityFullName(),
        ];
    }
}
