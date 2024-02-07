<?php declare(strict_types = 1);

namespace Ems\Microsoft;

use Microsoft\BingAds\Auth\ApiEnvironment;
use Microsoft\BingAds\Auth\AuthorizationData;
use Microsoft\BingAds\Auth\OAuthDesktopMobileAuthCodeGrant;
use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

// Disable WSDL caching.
ini_set("soap.wsdl_cache_enabled", 0);
ini_set("soap.wsdl_cache_ttl", 0);

class Ads
{
	private $authData = null;
	public ?ServiceClient $customerBillingProxy = null;
	public ?ServiceClient $customerManagementProxy = null;
	public ?ServiceClient $reportingProxy = null;

	function __construct($clientId, $developerToken, $refreshToken)
	{
		$authentication = (new OAuthDesktopMobileAuthCodeGrant())->withEnvironment(ApiEnvironment::Production)
		                                                         ->withClientId($clientId)
		                                                         ->withRedirectUri("https://login.live.com/oauth20_desktop.srf");

		$this->authData = (new AuthorizationData())->withAuthentication($authentication)
		                                           ->withDeveloperToken($developerToken);

		$this->authData->Authentication->RequestOAuthTokensByRefreshToken($refreshToken);

		// Customer Billing Proxy
		$this->customerBillingProxy = new ServiceClient(ServiceClientType::CustomerBillingVersion13, $this->authData, ApiEnvironment::Production);

		// Customer Management Proxy
		$this->customerManagementProxy = new ServiceClient(ServiceClientType::CustomerManagementVersion13, $this->authData, ApiEnvironment::Production);

		// Reporting Proxy
		$this->reportingProxy = new ServiceClient(ServiceClientType::ReportingVersion13, $this->authData, ApiEnvironment::Production);
		/**
		 * // If re-authenticating, ignore the following as it will not cause an empty response
		 * if(!$reauth)
		 * {
		 * $GLOBALS['microsoftAds']['authData']->Authentication->RequestOAuthTokensByRefreshToken($param['refreshToken']);
		 * $GLOBALS['microsoftAds']['customerBillingProxy'] = new ServiceClient(ServiceClientType::CustomerBillingVersion13, $GLOBALS['microsoftAds']['authData'], ApiEnvironment::Production);
		 * $GLOBALS['microsoftAds']['customerBillingProxy']->SetAuthorizationData($GLOBALS['microsoftAds']['authData']);
		 * $GLOBALS['microsoftAds']['customerManagementProxy'] = new ServiceClient(ServiceClientType::CustomerManagementVersion13, $GLOBALS['microsoftAds']['authData'], ApiEnvironment::Production
		 * );
		 * $GLOBALS['microsoftAds']['reportingProxy'] = new ServiceClient(ServiceClientType::ReportingVersion13, $GLOBALS['microsoftAds']['authData'], ApiEnvironment::Production);
		 * }
		 */
	}

}
