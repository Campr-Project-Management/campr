<?php

namespace AppBundle\Form\TrafficLight;

use Component\TrafficLight\TrafficLight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrafficLightType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices' => $this->getChoices(),
                'label' => 'label.traffic_light',
                'empty_data' => (string) TrafficLight::GREEN,
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @return array
     */
    private function getChoices(): array
    {
        return [
            'traffic_light.green' => TrafficLight::GREEN,
            'traffic_light.yellow' => TrafficLight::YELLOW,
            'traffic_light.red' => TrafficLight::RED,
        ];
    }
}
