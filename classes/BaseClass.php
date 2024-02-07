<?php declare(strict_types = 1);

namespace Ems;

class BaseClass
{
	/**
	 * Shortcut to set a value to an array and return the instance.
	 */
	protected function _setArray($value, ...$fields) : self
	{
		if(is_array($value))
		{
			static::_setValue($value, ...$fields);
		}

		else
		{
			static::_setValue([$value], ...$fields);
		}

		return $this;
	}

	/**
	 * Shortcut to set a value and return the instance.
	 */
	protected function _setValue($value, ...$fields) : self
	{
		$found   = true;
		$pointer =& $this;

		foreach($fields as $field)
		{
			if($found)
			{
				// For arrays
				if(is_array($pointer))
				{
					if(!isset($pointer[$field]))
					{
						$found = false;
					}

					else
					{
						$pointer =& $pointer[$field];
					}
				}

				// For objects
				else if(is_object($pointer))
				{
					if(!property_exists($pointer, $field))
					{
						$found = false;
					}

					else
					{
						$pointer =& $pointer->{$field};
					}
				}
			}
		}

		if($found)
		{
			$pointer = $value;
		}

		return $this;
	}

	/**
	 * Shortcut to add a value to an existing array.
	 */
	protected function _push($value, ...$fields) : self
	{
		$temp = $this->_get([], ...$fields);

		if(is_array($value))
		{
			$temp = $temp + $value;
		}
		else
		{
			$temp = $temp + [$value];
		}

		$this->_setValue($temp, ...$fields);

		return $this;
	}

	/**
	 * Shortcut to get a value, but default if the fields don't exist.
	 */
	protected function _get($defaultValue, ...$fields) : mixed
	{
		return Util::defaultTo($defaultValue, $this, ...$fields);
	}
}
