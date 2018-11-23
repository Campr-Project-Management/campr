<?php

namespace AppBundle\Serializer\Normalizer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonSerializableNormalizer implements NormalizerInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * JsonArrayNormalizer constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this
            ->serializer
            ->toArray($object, $context);
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return true;
    }
}
