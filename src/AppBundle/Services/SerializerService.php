<?php

namespace AppBundle\Services;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    /** @var  JsonEncoder */
    private $encoder;

    /** @var  GetSetMethodNormalizer */
    private $normalizer;

    /** @var  Serializer */
    private $serializer;

    public function __construct(JsonEncoder $encoder, GetSetMethodNormalizer $normalizer)
    {
        $callbackDateTime = function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format('d/m/Y')
                : ''
            ;
        };

        $this->encoder = $encoder;
        $this->normalizer = $normalizer;
        $this->normalizer->setCallbacks([
            'createdAt' => $callbackDateTime,
            'updatedAt' => $callbackDateTime,
        ]);
        $this->serializer = new Serializer([$this->normalizer], [$this->encoder]);
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }
}
