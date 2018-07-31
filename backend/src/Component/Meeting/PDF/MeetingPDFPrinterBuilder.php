<?php

namespace Component\Meeting\PDF;

use AppBundle\Entity\Meeting;
use Component\PDF\PDFPrinter;
use Component\PDF\PDFPrinterBuilder;
use Component\PDF\PDFPrinterInterface;

class MeetingPDFPrinterBuilder extends PDFPrinterBuilder
{
    const URL = 'meetings/%id%';

    /**
     * MeetingPDFPrinterBuilder constructor.
     *
     * @param string $baseUrl
     * @param string $binaryPath
     * @param string $binaryOptions
     */
    public function __construct(string $baseUrl, string $binaryPath, string $binaryOptions)
    {
        parent::__construct($baseUrl, $binaryPath, $binaryOptions);
    }

    /**
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function setMeeting(Meeting $meeting)
    {
        $this->setUrl(strtr(self::URL, ['%id%' => $meeting->getId()]));

        return $this;
    }

    /**
     * @return PDFPrinterInterface
     */
    protected function createPrinter(): PDFPrinterInterface
    {
        return new PDFPrinter($this->baseUrl, $this->binaryPath, $this->binaryOptions);
    }
}
