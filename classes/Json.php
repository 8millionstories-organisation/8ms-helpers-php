<?php declare(strict_types = 1);

namespace Ems;

class Json
{
	/**
	 * Safely decode a JSON input if possible, returning null if its not defined.
	 */
	static function getDecoded($input)
	{
		$response = @json_decode($input);

		if(JSON_ERROR_NONE !== json_last_error())
		{
			$response = null;
		}

		return $response;
	}

	/**
	 * Function to read a file then decode the JSON.
	 */
	static function getJsonFile(string $path)
	{
		$response = false;

		$raw = @file_get_contents($path);

		if($raw)
		{
			$decoded = @json_decode($raw, true);

			if(JSON_ERROR_NONE === json_last_error())
			{
				$response = $decoded;
			}
		}

		return $response;
	}

}
