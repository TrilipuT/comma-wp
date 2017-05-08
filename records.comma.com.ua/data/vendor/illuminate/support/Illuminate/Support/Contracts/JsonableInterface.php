<?php namespace Illuminate\Support\Contracts;

interface JsonableInterface {

	/**
	 * Convert the object to its JSON representation.
	 *
	 * @param  int  $options
	 * @return Strings
	 */
	public function toJson($options = 0);

}
