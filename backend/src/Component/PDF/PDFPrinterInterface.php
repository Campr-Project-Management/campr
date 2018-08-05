<?php

namespace Component\PDF;

use AppBundle\Entity\User;
use Component\PDF\Exception\PDFException;

interface PDFPrinterInterface
{
    /**
     * @param User $user
     */
    public function setUser(User $user);

    /**
     * @param string $host
     */
    public function setHost(string $host);

    /**
     * @param string $url
     */
    public function setUrl(string $url);

    /**
     * @return string
     *
     * @throws PDFException
     */
    public function getContents(): string;
}
