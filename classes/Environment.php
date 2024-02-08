<?php declare(strict_types = 1);

namespace Ems;

class Environment
{
	public static $environments = [
		'DEVELOPMENT' => 'DEVELOPMENT',
		'STAGING'     => 'STAGING',
		'PRODUCTION'  => 'PRODUCTION',
	];

	static function getEnvironment() : string
	{
		$response = self::$environments['PRODUCTION'];

		if(isset($_ENV['EMS_ENVIRONMENT']))
		{
			switch(strtoupper($_ENV['EMS_ENVIRONMENT']))
			{
				case self::$environments['DEVELOPMENT']:
					$response = self::$environments['DEVELOPMENT'];
					break;

				case self::$environments['STAGING']:
					$response = self::$environments['STAGING'];
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
		return self::$environments['DEVELOPMENT'] === self::getEnvironment();
	}

	static function isStaging() : bool
	{
		return self::$environments['STAGING'] === self::getEnvironment();
	}

	static function isProduction() : bool
	{
		return self::$environments['PRODUCTION'] === self::getEnvironment();
	}
}
