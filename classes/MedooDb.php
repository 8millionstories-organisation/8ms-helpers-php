<?php declare(strict_types = 1);

namespace Ems;

use Medoo\Medoo;

/**
 * https://medoo.in/
 * https://github.com/catfan/Medoo
 */
class MedooDb
{
	public ?Medoo $instance = null;

	function __construct($url = '', $database = '', $host = '', $pass = '', $user = '', $isPlanetScale = false, $type = 'mysql')
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

		// Parse details from URL
		if('' !== $url)
		{
			$auth = static::getAuthFromUrl($url);

			$type = $auth['type'];
			$host = $auth['host'];
			$database = $auth['database'];
			$user = $auth['username'];
			$pass = $auth['password'];
		}

		$this->instance = new Medoo(
			[
				'type'     => $type,
				'host'     => $host,
				'database' => $database,
				'username' => $user,
				'password' => $pass,
				'option'   => $option,
			],
		);
	}

	public static function getAuthFromUrl($url)
	{
		$response = [
			'type'     => '',
			'host'     => '',
			'database' => '',
			'username' => '',
			'password' => '',
			'option'   => '',
		];

		preg_match('/^(.*?):\/\/(.*?):(.*?)@(.*?):([0-9]*)\/(.*?)$/', $url, $matches);

		if(7 === count($matches))
		{
			$response['type'] = $matches[1];
			$response['host'] = $matches[4];
			$response['database'] = $matches[6];
			$response['username'] = $matches[2];
			$response['password'] = $matches[3];
		}

		return $response;
	}
}
