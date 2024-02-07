<?php declare(strict_types = 1);

namespace Ems\Google;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets as GoogleSheets;
use Google\Service\Sheets\ValueRange;

/**
 * Google Sheet API
 * https://developers.google.com/sheets/api/guides/values
 *
 * Note: Documentation refers to Google_Service_Sheets_ValueRange replace naming convention to => Google\Service\Sheets\ValueRange
 */
class Sheets
{
	public ?GoogleSheets $instance = null;
	public $sheetId = null;

	public function __construct($config, $sheetId)
	{
		$client = new Client();
		$client->setAuthConfig($config);
		$client->setScopes([
			Drive::DRIVE,
			GoogleSheets::SPREADSHEETS,
		]);

		$this->instance = new GoogleSheets($client);
		$this->sheetId  = $sheetId;
	}

	public function getRange($range)
	{
		return $this->instance->spreadsheets_values->get($this->sheetId, $range)->getValues();
	}

	public function writeRange($data, $range, $params = [])
	{
		$body = new ValueRange([
			'values' => $data
		]);

		$this->instance->spreadsheets_values->update(
			$this->sheetId,
			$range,
			$body,
			$this->_setValueParams($params)
		);
	}

	public function append($data, $range, $params = [])
	{
		$body = new ValueRange([
			'values' => $data
		]);

		$this->instance->spreadsheets_values->append(
			$this->sheetId,
			$range,
			$body,
			$this->_setValueParams($params)
		);
	}

	private function _setValueParams($params)
	{
		return [

			'valueInputOption' => 'RAW', // or 'USER_ENTERED' (for formulas)
			...$params,
		];
	}
}
