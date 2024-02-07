<?php declare(strict_types = 1);

namespace Ems;

use Medoo\Medoo;

/**
 * https://medoo.in/
 * https://github.com/catfan/Medoo
 */
class MedooDb
{
	private ?Medoo $instance = null;

	function __construct($database, $host, $pass, $user, $isPlanetScale = false, $type = 'mysql')
	{
		$option = [];

		if($isPlanetScale)
		{
			$option = [
				\PDO::ATTR_ERRMODE      => \PDO::ERRMODE_EXCEPTION,
				// Download from https://curl.se/docs/caextract.html
				\PDO::MYSQL_ATTR_SSL_CA => dirname(__FILE__) . '../../cacert-2023-08-22.pem',
			];
		}

		$this->instance = new Medoo([
			'type'     => $type,
			'host'     => $host,
			'database' => $database,
			'username' => $user,
			'password' => $pass,
			'option'   => $option,
		]);
	}

	public static function getUserFromUrl($url)
	{
		$response = '';

		preg_match('/mysql:\/\/(.*?):/', $url, $userMatch);

		if(2 === count($userMatch))
		{
			$response = $userMatch[1];
		}

		return $response;
	}

	public static function getPassFromUrl($url)
	{
		$response = '';

		preg_match('/mysql:\/\/.*?:(.*)@/', $url, $passwordMatch);

		if(2 === count($passwordMatch))
		{
			$response = $passwordMatch[1];
		}

		return $response;
	}
}
