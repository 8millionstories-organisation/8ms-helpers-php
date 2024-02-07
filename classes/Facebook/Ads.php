<?php declare(strict_types = 1);

namespace Ems\Facebook;

use FacebookAds\Api;

class Ads
{
	private ?Api $instance = null;

	function __construct($appId, $appSecret, $accessToken)
	{
		Api::init($appId, $appSecret, $accessToken);

		// The Api object is now available through singleton
		$this->instance = Api::instance();
	}
}
