<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Http\Request
 */
class Request extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'request'; }

}
