<?php declare(strict_types = 1);

namespace Ems;

class Date
{
	/**
	 * https://joshtronic.com/2013/11/04/handle-daylight-savings-time
	 */
	static function isDaylightSavings() : bool
	{
		return 1 === (int) date('I', time());
	}
}
