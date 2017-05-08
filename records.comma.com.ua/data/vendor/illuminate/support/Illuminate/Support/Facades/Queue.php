<?php namespace Illuminate\Support\Facades;

/**
 * @see \Illuminate\Queue\QueueManager
 * @see \Illuminate\Queue\Queue
 */
class Queue extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return Strings
	 */
	protected static function getFacadeAccessor() { return 'queue'; }

}
