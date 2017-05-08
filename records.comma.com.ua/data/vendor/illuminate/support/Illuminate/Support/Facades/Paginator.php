<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Pagination\Factory
 */
class Paginator extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'paginator'; }

}