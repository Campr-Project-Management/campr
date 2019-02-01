<?php

namespace Component\Project\Settings;

use Component\Settings\SchemaInterface;
use Component\Settings\SettingsBuilderInterface;
use Component\StatusReport\Aggregator\StatusReportsAggregatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectSettingsSchema implements SchemaInterface
{
    /**
     * @param SettingsBuilderInterface $builder
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(
                [
                    'statusReportTrendChartAggregatorType' => StatusReportsAggregatorInterface::TYPE_WEEKLY,
                ]
            )
            ->setAllowedTypes(
                [
                    'statusReportTrendChartAggregatorType' => ['string'],
                ]
            )
            ->setAllowedValues(
                [
                    'statusReportTrendChartAggregatorType' => [
                        StatusReportsAggregatorInterface::TYPE_DAILY,
                        StatusReportsAggregatorInterface::TYPE_WEEKLY,
                        StatusReportsAggregatorInterface::TYPE_BIWEEKLY,
                        StatusReportsAggregatorInterface::TYPE_MONTHLY,
                    ],
                ]
            );
    }

    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder->add(
            'statusReportTrendChartAggregatorType',
            ChoiceType::class,
            [
                'required' => true,
                'label' => 'label.status_report_trend_chart_agg_type',
                'choices' => $this->getStatusReportTrendChartAggregatorTypeChoices(),
            ]
        );
    }

    /**
     * @return array
     */
    private function getStatusReportTrendChartAggregatorTypeChoices(): array
    {
        return [
            'message.daily' => StatusReportsAggregatorInterface::TYPE_DAILY,
            'message.weekly' => StatusReportsAggregatorInterface::TYPE_WEEKLY,
            'message.biweekly' => StatusReportsAggregatorInterface::TYPE_BIWEEKLY,
            'message.monthly' => StatusReportsAggregatorInterface::TYPE_MONTHLY,
        ];
    }
}
