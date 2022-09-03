<?php
namespace MMierzynski\GusApi\Client;

class SoapClient extends \SoapClient
{
    public function __doRequest(string $request, string $location, string $action, int $version, bool $one_way = false): ?string 
	{
		$response = parent::__doRequest($request, $location, $action, $version, $one_way);
		/*if (strpos($response, "Content-Type: application/xop+xml") !== false) {
			$tempstr = stristr($response, "<s:");
			$response = substr($tempstr, 0, strpos($tempstr, "</s:Envelope>")) . "</s:Envelope>";
		}*/
		return $response;
	}
}
