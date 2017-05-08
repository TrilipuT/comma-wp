<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Auth\AuthManager
 * @see \Illuminate\Auth\Guard
 */
class Auth extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'auth'; }

}
