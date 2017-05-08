<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Foundation\Artisan
 */
class Artisan extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'artisan'; }

}
