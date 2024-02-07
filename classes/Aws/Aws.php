<?php declare(strict_types = 1);

namespace Ems\Aws;

use Ems\Environment;

class Aws
{
	public static function getConfig($region)
	{
		$config = [
			'region'  => $region,
			'version' => '2006-03-01',
		];

		if(Environment::isLocalhostRequest() || !Environment::isAws())
		{
			$config['credentials'] = [
				'key'    => $_SERVER['AWS_IAM_ACCESS_KEY_ID'],
				'secret' => $_SERVER['AWS_IAM_SECRET_KEY'],
			];
		}

		return $config;
	}

	public static function isResponse200($apiResponse) : bool
	{
		$response = false;

		if(isset($apiResponse['@metadata']) && isset($apiResponse['@metadata']['statusCode']))
		{
			$response = 200 === $apiResponse['@metadata']['statusCode'];
		}

		return $response;
	}
}
