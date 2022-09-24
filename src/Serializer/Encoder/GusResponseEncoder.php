<?php
namespace MMierzynski\GusApi\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class GusResponseEncoder extends XmlEncoder
{
    public function decode(string $data, string $format, array $context = []): mixed
    {
        $decodedData = parent::decode($data, $format, $context);

        return $decodedData['dane'];
    }
}