<?php

namespace Component\Snapshot;

use Component\Snapshot\Model\SnapshotInterface;

class Snapshot implements SnapshotInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * Snapshot constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = [
            'data' => $data,
            'meta' => [
                'createdAt' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data['data'] ?? [];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data['data'] ?? [];
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->data['meta']['createdAt']);
    }
}
