<?php

namespace AppBundle\Paginator;

interface SerializablePaginatorInterface
{
    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @return int
     */
    public function getNbPages(): int;

    /**
     * @return int
     */
    public function getNbItems(): int;

    /**
     * @param mixed $item
     */
    public function addItem($item);

    /**
     * @param array|\Traversable $item
     */
    public function setItems($item);

    /**
     * @param int $nbPages
     */
    public function setNbPages(int $nbPages);

    /**
     * @param int $nbItems
     */
    public function setNbItems(int $nbItems);
}
