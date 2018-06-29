<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\Todo;
use AppBundle\Repository\TodoRepository;

class TodosTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * TodosTransformer constructor.
     *
     * @param TransformerInterface $dateTransformer
     * @param TodoRepository       $todoRepository
     */
    public function __construct(TransformerInterface $dateTransformer, TodoRepository $todoRepository)
    {
        $this->dateTransformer = $dateTransformer;
        $this->todoRepository = $todoRepository;
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
        $todos = $this->todoRepository->getAllForStatusReport($project);

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
            'createdAt' => $this->dateTransformer->transform($todo->getCreatedAt()),
            'categoryId' => $todo->getTodoCategoryId(),
            'categoryName' => $todo->getTodoCategoryName(),
            'responsibilityId' => $todo->getResponsibilityId(),
            'responsibilityFullName' => $todo->getResponsibilityFullName(),
        ];
    }
}
