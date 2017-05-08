<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Session\SessionManager
 * @see \Illuminate\Session\Store
 */
class Session extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'session'; }

}
