<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Routing\Router
 */
class Route extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'router'; }

}
