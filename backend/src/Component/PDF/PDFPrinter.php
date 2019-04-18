<?php

namespace Component\PDF;

use AppBundle\Entity\User;
use Component\PDF\Exception\PDFException;
use Symfony\Component\Process\Process;

class PDFPrinter implements PDFPrinterInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $host;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $binaryPath;

    /**
     * @var string
     */
    private $binaryOptions;

    /**
     * PDFPrinter constructor.
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
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @throws PDFException
     *
     * @return string
     */
    public function getContents(): string
    {
        $path = tempnam('/tmp', md5($this->url));

        $query = [
            'host' => $this->host,
            'key' => $this->user->getApiToken(),
            'locale' => $this->user->getlocale(),
        ];

        $options = strtr(
            $this->binaryOptions,
            [
                '%url%' => sprintf('%s%s?%s', $this->baseUrl, $this->url, http_build_query($query)),
                '%file%' => $path,
            ]
        );

        $process = new Process(
            $this->binaryPath.' '.$options,
            '/tmp'
        );

        $process->run();

        if (!$process->isSuccessful()) {
            throw new PDFException();
        }

        $contents = file_get_contents($path);
        @unlink($path);

        return $contents;
    }
}
