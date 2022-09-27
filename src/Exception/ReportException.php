<?php
namespace MMierzynski\GusApi\Exception;

use Throwable;

class ReportException extends \Exception
{
    protected string $messagePl;

    public function __construct(string $messagePl = "", string $messageEn = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($messageEn, $code, $previous);
        $this->messagePl = $messagePl;
    }

    public function getMessageEn(): string
    {
        return $this->message;
    }

    public function getMessagePl(): string
    {
        return $this->messagePl;
    }
}