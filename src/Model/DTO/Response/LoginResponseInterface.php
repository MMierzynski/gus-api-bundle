<?php
namespace MMierzynski\GusApi\Model\DTO\Response;

interface LoginResponseInterface
{
    public function getAccessKey(): ?string;
}