<?php

namespace AppBundle\Services;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Entity\StatusReport;
use AppBundle\Entity\User;
use Component\Meeting\PDF\MeetingPDFPrinterBuilder;
use Component\PDF\Exception\PDFException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class PDF
{
    const CONTRACT_URL = 'projects/%id%/contract';
    const PROJECT_CLOSE_DOWN_URL = 'projects/%id%/close-down-report';
    const STATUS_REPORT_URL = 'projects/%projectID%/status-reports/view-status-report/%statusReportID%';

    /** @var RequestStack */
    private $requestStack;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var string */
    private $binaryPath;

    /** @var string */
    private $binaryOptions;

    /** @var string */
    private $serviceUrl;

    /**
     * @var MeetingPDFPrinterBuilder
     */
    private $meetingPDFPrinterBuilder;

    /**
     * PDF constructor.
     *
     * @param RequestStack             $requestStack
     * @param TokenStorageInterface    $tokenStorage
     * @param string                   $binaryPath
     * @param string                   $binaryOptions
     * @param string                   $serviceUrl
     * @param MeetingPDFPrinterBuilder $meetingPDFPrinterBuilder
     */
    public function __construct(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        string $binaryPath,
        string $binaryOptions,
        string $serviceUrl,
        MeetingPDFPrinterBuilder $meetingPDFPrinterBuilder
    ) {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->binaryPath = $binaryPath;
        $this->binaryOptions = $binaryOptions;
        $this->serviceUrl = $serviceUrl;
        $this->meetingPDFPrinterBuilder = $meetingPDFPrinterBuilder;
    }

    /**
     * @throws \LogicException
     *
     * @return User
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            throw new \LogicException('I can only print for authenticated users. #1');
        }

        $user = $token->getUser();

        if (!is_object($user)) {
            throw new \LogicException('I can only print for authenticated users. #2');
        }

        return $user;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return null|string
     */
    private function run(string $url, array $params)
    {
        $tmpFile = tempnam('/tmp', md5($url));
        $user = $this->getUser();

        $query = [
            'host' => $this->requestStack->getMasterRequest()->getHttpHost(),
            'key' => $user->getApiToken(),
            'locale' => $user->getLocale(),
        ];

        $options = strtr($this->binaryOptions, [
            '%url%' => $this->serviceUrl.strtr($url, $params).'?'.http_build_query($query),
            '%file%' => $tmpFile,
        ]);

        $cmd = sprintf('%s %s', $this->binaryPath, $options);
        $process = new Process($cmd, '/tmp');

        $process->run();

        return $process->isSuccessful() ? $tmpFile : null;
    }

    public function getContractPDF(Contract $contract)
    {
        return $this->run(self::CONTRACT_URL, ['%id%' => $contract->getProject()->getId()]);
    }

    public function getProjectCloseDownPDF(int $id)
    {
        return $this->run(self::PROJECT_CLOSE_DOWN_URL, ['%id%' => $id]);
    }

    /**
     * @param Meeting $meeting
     *
     * @throws PDFException
     *
     * @return string
     */
    public function getMeetingPDF(Meeting $meeting)
    {
        return $this
            ->meetingPDFPrinterBuilder
            ->setMeeting($meeting)
            ->setHost($this->requestStack->getMasterRequest()->getHttpHost())
            ->setUser($this->getUser())
            ->getPrinter()
            ->getContents()
        ;
    }

    /**
     * @param StatusReport $statusReport
     *
     * @return bool|null|string
     */
    public function getStatusReportPDF(StatusReport $statusReport)
    {
        Assert::integer($statusReport->getId());
        Assert::isInstanceOf($statusReport->getProject(), Project::class);
        Assert::integer($statusReport->getProject()->getId());

        return $this->run(self::STATUS_REPORT_URL, [
            '%projectID%' => $statusReport->getProjectId(),
            '%statusReportID%' => $statusReport->getId(),
        ]);
    }
}
