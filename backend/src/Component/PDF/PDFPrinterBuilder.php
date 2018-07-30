<?php

namespace Component\PDF;

use AppBundle\Entity\User;

class PDFPrinterBuilder
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $binaryPath;

    /**
     * @var string
     */
    protected $binaryOptions;

    /**
     * @var PDFPrinterInterface
     */
    private $printer;

    /**
     * PDFPrinterBuilder constructor.
     *
     * @param string $baseUrl
     * @param string $binaryPath
     * @param string $binaryOptions
     */
    public function __construct(string $baseUrl, string $binaryPath, string $binaryOptions)
    {
        $this->baseUrl = $baseUrl;
        $this->binaryPath = $binaryPath;
        $this->binaryOptions = $binaryOptions;
        $this->printer = $this->createPrinter();
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->printer->setUser($user);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->printer->setUrl($url);

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->printer->setHost($host);

        return $this;
    }

    /**
     * @return PDFPrinterInterface
     */
    public function getPrinter(): PDFPrinterInterface
    {
        $printer = $this->printer;
        $this->printer = $this->createPrinter();

        return $printer;
    }

    /**
     * @return PDFPrinterInterface
     */
    protected function createPrinter(): PDFPrinterInterface
    {
        return new PDFPrinter($this->baseUrl, $this->binaryPath, $this->binaryOptions);
    }
}
