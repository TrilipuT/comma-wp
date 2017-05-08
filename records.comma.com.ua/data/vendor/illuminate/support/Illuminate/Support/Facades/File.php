<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Filesystem\Filesystem
 */
class File extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'files'; }

}
