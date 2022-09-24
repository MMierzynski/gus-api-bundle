<?php
namespace MMierzynski\GusApi\Serializer\Normalizer;

use MMierzynski\GusApi\Model\DTO\CompanyDetails;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CompanyDetailsDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []) 
    {
        if ($data['ErrorCode']) {
            throw new \Exception($data['ErrorMessageEn']);
        }


        /** @var CompanyDetails $object */
        $object =  new CompanyDetails();
        $object->setRegon($data['Regon'])
            ->setNip($data['Nip'])
            ->setNipStatus($data['StatusNip'])
            ->setName($data['Nazwa'])
            ->setVoivodeship($data['Wojewodztwo'] ?? '')
            ->setCounty($data['Powiat'] ?? '')
            ->setCommunity($data['Gmina'] ?? '')
            ->setCity($data['Miejscowosc'] ?? '')
            ->setZip($data['KodPocztowy'] ?? '')
            ->setStreet($data['Ulica'] ?? '')
            ->setHouseNo($data['NrNieruchomosci'] ?? '')
            ->setFlatNo($data['NrLokalu'] ?? '')
            ->setType($data['Typ'] ?? '')
            ->setSilosId($data['SilosId'] ?? '')
            ->setterminationDate($data['DataZakonczeniaDzialalnosci'] ?? '')
            ->setPostCity($data['MiejscowoscPoczty'] ?? '');

        return $object;
    }


    public function supportsDenormalization(mixed $data, string $type, string $format = null) 
    {   
        return CompanyDetails::class === $type;
    }
}
