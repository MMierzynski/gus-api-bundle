<?php
namespace MMierzynski\GusApi\Serializer\Normalizer;

use MMierzynski\GusApi\Exception\ReportException;
use MMierzynski\GusApi\Model\DTO\Report;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReportDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (isset($data['ErrorCode'])) {
            throw new ReportException($data['ErrorMessagePl'], $data['ErrorMessageEn'], $data['ErrorCode']);
        }

        $object = new Report();
        $object->setReportData($data);

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null) 
    {   
        return Report::class === $type;
    }
}