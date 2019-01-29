<?php

namespace Component\Project\Settings;

use AppBundle\Entity\Project;
use Component\Settings\SchemaInterface;
use Component\Settings\Settings;
use Component\Settings\SettingsBuilder;
use Component\Settings\SettingsInterface;

class ProjectSettingsManager
{
    /**
     * @var SchemaInterface
     */
    private $schema;

    /**
     * ProjectSettingsManager constructor.
     *
     * @param SchemaInterface $schema
     */
    public function __construct(SchemaInterface $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param Project $project
     *
     * @return SettingsInterface
     */
    public function load(Project $project): SettingsInterface
    {
        $parameters = $project->getConfiguration();
        if (empty($parameters)) {
            $parameters = [];
        }

        $builder = new SettingsBuilder();
        $this->schema->buildSettings($builder);

        foreach ($parameters as $name => $value) {
            if (!$builder->isDefined($name)) {
                unset($parameters[$name]);
            }
        }

        $parameters = $builder->resolve($parameters);
        $settings = new Settings();
        $settings->setParameters($parameters);

        return $settings;
    }
}
