<?php

namespace Component\Resource\Model;

use Component\User\Model\UserInterface;

trait BlameableTrait
{
    /**
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * @var UserInterface
     */
    protected $updatedBy;

    /**
     * @return UserInterface|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface|null $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return UserInterface|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface|null $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;
    }
}
