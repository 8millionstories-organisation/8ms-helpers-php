<?php declare(strict_types = 1);

namespace Ems\Aws;

use Aws\Ssm\SsmClient;
use Ems\Json;

class Ssm
{
	public ?SsmClient $instance = null;

	function __construct($region)
	{
		$config = Aws::getConfig(region: $region);

		$this->instance = new SsmClient([
			...$config,
			'version' => '2014-11-06',
		]);
	}

	function getParameter($name, $withDecryption = true, $isJson = true)
	{
		$response = false;

		$apiResponse = $this->instance->getParameter([
			'Name'           => $name,
			'WithDecryption' => $withDecryption,
		]);

		if(Aws::isResponse200($apiResponse))
		{
			$response = $apiResponse->toArray()['Parameter']['Value'];

			if($isJson)
			{
				$response = Json::getJsonFile($response);
			}
		}

		return $response;
	}
}
