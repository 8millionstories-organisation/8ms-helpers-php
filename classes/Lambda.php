<?php declare(strict_types = 1);

namespace Ems;

class Lambda
{
	/**
	 * Shorthand function to deal with the response after lambda has run.
	 */
	static function send($examplePayload, $payload, $context) : string
	{
		$response = [
			...Api::DefaultResponse,
		];

		try
		{
			if(null !== $examplePayload && (Environment::isLocalhostRequest() || (Environment::isDevelopment() && null === $payload)))
			{
				$payload = $examplePayload;
			}

			$response['body']  = call_user_func('main', $payload, $context);
			$response['state'] = Api::States['SUCCESS'];
		}
		catch(\Exception $exception)
		{
			$response['state'] = Api::States['ERROR'];
			$response['error'] = $exception->getMessage();
		}

		// Decode into string
		$response = json_encode($response, JSON_UNESCAPED_SLASHES);

		// JSON response
		@header('Content-type: application/json');

		if(Environment::isLocalhostRequest())
		{
			echo $response;
			die();
		}

		return $response;
	}

}
