<?php

namespace AppBundle\Event;

use AppBundle\Entity\Rasci;
use Symfony\Component\EventDispatcher\Event;

class RasciEvent extends Event
{
    /**
     * @var Rasci
     */
    private $rasci;

    /**
     * RasciEvent constructor.
     *
     * @param Rasci $rasci
     */
    public function __construct(Rasci $rasci)
    {
        $this->rasci = $rasci;
    }

    /**
     * @return Rasci
     */
    public function getRasci(): Rasci
    {
        return $this->rasci;
    }
}
