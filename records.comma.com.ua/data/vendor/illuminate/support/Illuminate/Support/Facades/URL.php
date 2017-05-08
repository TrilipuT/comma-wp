<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Routing\UrlGenerator
 */
class URL extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'url'; }

}
