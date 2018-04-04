<?php

namespace AppBundle\Paginator;

use Pagerfanta\Pagerfanta;

class SerializablePagerfanta extends SerializablePaginator
{
    /**
     * SerializablePagerfanta constructor.
     *
     * @param Pagerfanta $pagerfanta
     */
    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->setItems($pagerfanta->getCurrentPageResults());
        $this->setNbPages($pagerfanta->getNbPages());
        $this->setNbItems($pagerfanta->getNbResults());
    }
}
