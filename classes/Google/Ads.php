<?php declare(strict_types = 1);

namespace Ems\Google;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V15\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V15\GoogleAdsClientBuilder;

class Ads
{
	public ?GoogleAdsClient $instance = null;

	function __construct($clientId, $clientSecret, $developerToken, $refreshToken, $mccAccountId)
	{
		// Generate a refreshable OAuth2 credential for authentication.
		$oAuth2Credential = (new OAuth2TokenBuilder())->withClientId($clientId)
		                                              ->withClientSecret($clientSecret)
		                                              ->withRefreshToken($refreshToken)
		                                              ->build();

		// Construct a Google Ads client configured from a properties file and the
		// OAuth2 credentials above.
		$this->instance = (new GoogleAdsClientBuilder())->withDeveloperToken($developerToken)
		                                                ->withLoginCustomerId((int) $mccAccountId)
		                                                ->withOAuth2Credential($oAuth2Credential)
		                                                ->build();
	}

	/**
	 * Function to convert a micro (millions) into Base (normal)
	 */
	public static function getBaseFromMicro($amount) : float
	{
		return $amount ? $amount / 1000000.0 : 0.0;
	}

	/**
	 * Function to convert a Base (normal) into micro (millions)
	 */
	public static function getMicroFromBase($amount) : float
	{
		return $amount ? $amount * 1000000.0 : 0.0;
	}
}
