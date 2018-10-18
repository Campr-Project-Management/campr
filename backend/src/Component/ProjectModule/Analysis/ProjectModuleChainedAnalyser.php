<?php

namespace Component\ProjectModule\Analysis;

class ProjectModuleChainedAnalyser implements ProjectModuleAnalyserInterface
{
    /**
     * @var ProjectModuleAnalyserInterface[]
     */
    private $analysers = [];

    /**
     * ProjectModuleAnalyser constructor.
     *
     * @param ProjectModuleAnalyserInterface[] $analysers
     */
    public function __construct(...$analysers)
    {
        foreach ($analysers as $analyser) {
            $this->addAnalyser($analyser);
        }
    }

    /**
     * @param ProjectModuleAnalyserInterface $analyser
     */
    public function addAnalyser(ProjectModuleAnalyserInterface $analyser)
    {
        $this->analysers[$analyser->getType()] = $analyser;
    }

    /**
     * @param string $module
     * @param int    $value
     * @param string $type
     *
     * @return bool
     */
    public function analyse(string $module, int $value, string $type = null): bool
    {
        $type = (string) $type;
        $this->ensureSupportedType($type);

        foreach ($this->analysers as $analyser) {
            if (!$analyser->supportsType($type)) {
                continue;
            }

            if ($analyser->analyse($module, $value, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsType(string $type): bool
    {
        return isset($this->analysers[$type]);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'project_module_analyser';
    }

    /**
     * @param string $type
     */
    private function ensureSupportedType(string $type)
    {
        if ($this->supportsType($type)) {
            return;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Type "%s" is not supported. Supported types: %s',
                $type,
                implode(', ', array_keys($this->analysers))
            )
        );
    }
}
