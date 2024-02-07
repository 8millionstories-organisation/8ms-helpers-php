<?php declare(strict_types = 1);

namespace Ems\Google;

use Google\Cloud\BigQuery\BigQueryClient;

class BigQuery
{
	private ?BigQueryClient $instance = null;

	function __construct($keyFile, $projectId)
	{
		$this->instance = new BigQueryClient([
			'keyFile'   => $keyFile,
			'projectId' => $projectId,
		]);
	}

	public function getResults($query, $isSingle = false)
	{
		$response = [];

		// Run a query and inspect the results.
		$queryJobConfig = $this->instance->query($query);
		$queryResults   = $this->instance->runQuery($queryJobConfig);
		$queryResults->waitUntilComplete();

		// https://cloud.google.com/php/docs/reference/cloud-bigquery/latest/QueryResults#_Google_Cloud_BigQuery_QueryResults__rows__
		$rows = $queryResults->rows();

		foreach($rows as $index => $row)
		{
			if(($isSingle && 0 === $index) || !$isSingle)
			{
				$response[] = $row;
			}
		}

		return $response;
	}


	/**
	 * https://cloud.google.com/bigquery/docs/samples/bigquery-load-table-gcs-json
	 */
	public function loadData($query, $isSingle = false)
	{
		// @todo
	}
}
