<?php 
namespace MMierzynski\GusApi\Serializer;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use MMierzynski\GusApi\Serializer\Encoder\GusResponseEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use MMierzynski\GusApi\Serializer\Normalizer\CompanyDetailsDenormalizer;
use MMierzynski\GusApi\Serializer\Normalizer\ReportDenormalizer;

class ResponseDeserializer
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $encoders = [new GusResponseEncoder()];
        $normalizers = [new CompanyDetailsDenormalizer(), new ReportDenormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function deserialize(mixed $data, string $type): mixed {
        return $this->serializer->deserialize(
            $data, 
            $type, 
            'xml', 
            [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                'xml_root_node_name' => 'dane'
            ]
        );
    }
}