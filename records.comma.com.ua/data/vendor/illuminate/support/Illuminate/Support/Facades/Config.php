<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Config\Repository
 */
class Config extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'config'; }

}
