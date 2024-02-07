<?php declare(strict_types = 1);

namespace Ems;

class Environment
{
	const ENVIRONMENTS = [
		'DEVELOPMENT' => 'development',
		'STAGING'     => 'staging',
		'PRODUCTION'  => 'production',
	];

	static function getEnvironment() : string
	{
		$response = self::ENVIRONMENTS['PRODUCTION'];

		if(isset($_ENV['EMS_ENVIRONMENT']))
		{
			switch($_ENV['EMS_ENVIRONMENT'])
			{
				case 'development':
					$response = self::ENVIRONMENTS['DEVELOPMENT'];
					break;

				case 'staging':
					$response = self::ENVIRONMENTS['STAGING'];
					break;
			}
		}

		return $response;
	}

	/**
	 * Function to quickly check if the function is ran on AWS.
	 */
	static function isAws() : bool
	{
		return isset($_SERVER['AWS_LAMBDA_FUNCTION_NAME']);
	}

	static function isLocalhostRequest() : bool
	{
		return isset($_SERVER['HTTP_HOST']) && 'localhost' === $_SERVER['HTTP_HOST'];
	}

	static function isDevelopment() : bool
	{
		return ENVIRONMENTS['DEVELOPMENT'] === self::getEnvironment();
	}

	static function isStaging() : bool
	{
		return ENVIRONMENTS['STAGING'] === self::getEnvironment();
	}

	static function isProduction() : bool
	{
		return ENVIRONMENTS['PRODUCTION'] === self::getEnvironment();
	}
}
