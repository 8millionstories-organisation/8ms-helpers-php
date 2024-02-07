<?php declare(strict_types = 1);

namespace Ems\Aws;

use Aws\S3\S3Client;
use Ems\Json;

class S3
{
	public ?S3Client $instance = null;

	function __construct($region)
	{
		$config = Aws::getConfig(region: $region);

		$this->instance = new S3Client([
			...$config,
			'version' => '2006-03-01',
		]);
	}

	/**
	 * Read a file from S3 and return either null, the content or JSON decoded.
	 */
	function readFile($bucket, $key, $isJson = true)
	{
		$response = false;

		try
		{
			// Register the wrapper, so we can use s3://
			$this->instance->registerStreamWrapper();

			// Make the API Request
			$apiResponse = $this->instance->getObject([
				'Bucket' => $bucket,
				'Key'    => $key,
			]);

			if(Aws::isResponse200($apiResponse))
			{
				if($stream = fopen("s3://{$bucket}/{$key}", 'r'))
				{
					// While the stream is still open
					while(!feof($stream))
					{
						// Read 1,024 bytes from the stream
						$response = fread($stream, 1024);
					}

					// Be sure to close the stream resource when you're done with it
					fclose($stream);
				}

				if($isJson)
				{
					$response = Json::getDecoded($response);
				}
			}
		}
		catch(\Exception $exception)
		{
			// Do nothing is okay
		}

		return $response;
	}


	/**
	 * Delete a single file.
	 */
	function deleteFile($bucket, $key): void
	{
		$apiResponse = $this->instance->deleteObject([
			'Bucket' => $bucket,
			'Key'    => $key,
		]);
	}


	/**
	 * Write a file to S3.
	 */
	function writeFile($bucket, $data, $key) : void
	{
		$apiResponse = $this->instance->putObject([
			'Bucket' => $bucket,
			'Body'   => $data,
			'Key'    => $key,
		]);
	}
}
