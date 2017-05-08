<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Hashing\BcryptHasher
 */
class Hash extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'hash'; }

}
