<?php
namespace MMierzynski\GusApi\Exception;

class InvalidReportDateException extends ReportException
{
    protected string $messagePl = 'Wprowadzono datę przyszłą';
    protected $message = 'A future date has been entered';
    protected $code = 103;
}