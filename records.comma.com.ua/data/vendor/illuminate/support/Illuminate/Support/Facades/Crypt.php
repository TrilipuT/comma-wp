<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Encryption\Encrypter
 */
class Crypt extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'encrypter'; }

}
