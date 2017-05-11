<?php

namespace BestProject\Wordpress;

/**
 * Just a basic structure for basic class methods.
 */
trait Object
{

	/**
	 * Get a given property.
	 *
	 * @param	String	$property		Name of a property to get.
	 * @param	Mixed	$default_value	Default value to return in case property was emtpy or not found.
	 * @return	Mixed
	 */
	public function get($property, $default_value = null)
	{

		if (isset($this->$property)) {
			return $this->$property;
		} else {
			return $default_value;
		}
	}

	/**
	 * Sets a given property.
	 *
	 * @param	Strong	$property	A property to set.
	 * @param	Mixed	$value		Value to set.
	 *
	 * @throws ErrorException
	 */
	public function set($property, $value = true)
	{
		$className = get_called_class();

		if (property_exists($this, $property)) {
			$this->$property = $value;
		} else {
			throw new ErrorException('Property <b>'.$property.'</b> does not exists in <b>'.$className.'</b>',
			500);
		}
	}
}