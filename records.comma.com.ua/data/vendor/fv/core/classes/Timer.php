<?php

class Timer {

    static $timers = array();

    static function start( $name = null ){
        self::$timers[$name] = microtime(true);
    }

    static function stop( $name = null ){
        $time = self::get( $name );
        self::clearTimer( $name );
        return $time;
    }

    static function get( $name = null ){
        return microtime(true) - self::$timers[$name];
    }

    public static function output( $name = null ){
        print self::get($name) . "<br/>";
    }

    public static function outputCircle( $name = null ){
        print self::get($name) . "<br/>";
        self::start( $name );
    }

    public static function circle( $name = null ){
        $time = self::get( $name );
        self::start( $name );
        return $time;
    }

    private static function clear( $name ){
        unset( self::$timers[$name] );
    }

}
