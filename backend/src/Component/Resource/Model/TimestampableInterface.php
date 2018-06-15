<?php

namespace Component\Resource\Model;

interface TimestampableInterface
{
    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt();
    /**
     * @param \DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt = null);
    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt();
    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt = null);
}
