<?php

namespace AppBundle\Paginator;

use JMS\Serializer\Annotation as Serializer;

class SerializablePaginator implements SerializablePaginatorInterface
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $nbPages = 0;

    /**
     * @var int
     */
    protected $nbItems = 0;

    /**
     * @Serializer\VirtualProperty()
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int
     */
    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int
     */
    public function getNbItems(): int
    {
        return $this->nbItems;
    }

    /**
     * @param mixed $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * @param int $nbPages
     */
    public function setNbPages(int $nbPages)
    {
        $this->nbPages = $nbPages;
    }

    /**
     * @param int $nbItems
     */
    public function setNbItems(int $nbItems)
    {
        $this->nbItems = $nbItems;
    }

    /**
     * @param array|\Traversable $items
     */
    public function setItems($items)
    {
        if ($items instanceof \Traversable) {
            $items = iterator_to_array($items);
        }

        $this->items = $items;
    }
}
