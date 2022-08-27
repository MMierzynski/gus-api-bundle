<?php
namespace MMierzynski\GusApi\Query;

interface QueryInterface
{
    public function getActionName(): string;

    public function getActionUrl(): string;
}