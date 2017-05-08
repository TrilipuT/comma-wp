<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Mail\Mailer
 */
class Mail extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'mailer'; }

}
