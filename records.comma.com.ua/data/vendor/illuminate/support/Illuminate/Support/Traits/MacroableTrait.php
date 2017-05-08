<?php namespace Illuminate\Support\Traits;

trait MacroableTrait {

	/**
	 * The registered string macros.
	 *
	 * @var array
	 */
	protected static $macros = array();

	/**
	 * Register a custom macro.
	 *
	 * @param  Strings $name
	 * @param  callable  $macro
	 *
	 * @return void
	 */
	public static function macro($name, callable $macro)
	{
		static::$macros[$name] = $macro;
	}

	/**
	 * Checks if macro is registered
	 *
	 * @param  Strings    $name
	 *
	 * @return boolean
	 */
	public static function hasMacro($name)
	{
		return isset(static::$macros[$name]);
	}

	/**
	 * Dynamically handle calls to the class.
	 *
	 * @param  Strings  $method
	 * @param  array   $parameters
	 *
	 * @return mixed
	 *
	 * @throws \BadMethodCallException
	 */
	public static function __callStatic($method, $parameters)
	{
		if (static::hasMacro($method))
		{
			return call_user_func_array(static::$macros[$method], $parameters);
		}

		throw new \BadMethodCallException("Method {$method} does not exist.");
	}

	/**
	 * Dynamically handle calls to the class.
	 *
	 * @param  Strings  $method
	 * @param  array   $parameters
	 *
	 * @return mixed
	 *
	 * @throws \BadMethodCallException
	 */
	public function __call($method, $parameters)
	{
		return static::__callStatic($method, $parameters);
	}

}
