<?php

namespace Component\Settings;

use Symfony\Component\Form\FormBuilderInterface;

interface SchemaInterface
{
    /**
     * @param SettingsBuilderInterface $builder
     */
    public function buildSettings(SettingsBuilderInterface $builder);

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder);
}
