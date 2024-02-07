<?php declare(strict_types = 1);

namespace Ems\Google;

use Google\Cloud\Storage\StorageClient;

class Storage
{
	private ?StorageClient $instance = null;

	function __construct($keyFile, $projectId)
	{
		$this->instance = new StorageClient([
			'keyFile'   => $keyFile,
			'projectId' => $projectId,
		]);
	}

	/**
	 * https://github.com/googleapis/google-cloud-php-storage/blob/main/tests/System/UploadObjectsTest.php#L69
	 */
	public function writeFile($bucket, $data, $key)
	{
		$bucket = $this->instance->bucket($bucket);

		if(!$stream = fopen('data://text/plain,' . $data, 'r'))
		{
			throw new \InvalidArgumentException('Unable to open file for reading');
		}

		$bucket->upload($stream, [
			'name' => $key,
		]);
	}
}
