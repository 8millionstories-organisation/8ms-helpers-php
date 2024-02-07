<?php declare(strict_types = 1);

namespace Ems;

class Util
{
	/**
	 * Function to default to a value, whilst filtering through an instance
	 */
	public static function defaultTo($defaultValue, $instance, ...$keys) : mixed
	{
		$found   = true;
		$pointer =& $instance;

		// Instance is undefined
		if(null === $instance)
		{
			$found = false;
		}

		// Instance exists
		else
		{
			foreach($keys as $key)
			{
				if($found)
				{
					// For arrays
					if(is_array($pointer))
					{
						if(!isset($pointer[$key]))
						{
							$found = false;
						}

						else
						{
							$pointer =& $pointer[$key];
						}
					}

					// For objects
					else if(is_object($pointer))
					{
						if(!property_exists($pointer, $key))
						{
							$found = false;
						}

						else
						{
							$pointer =& $pointer->{$key};
						}
					}
				}
			}
		}

		return $found ? $pointer : $defaultValue;
	}
}
