<?php

final class fvSite {

    /** @var fvApp */
    private static $app;

    public static function init(){
        if( fvSite::config()->get( "debug" ) ){
            define( "FV_DEBUG_MODE", true );
        }
    }

    public static function initApp( fvApp $app ) {
        self::$app = $app;
    }

    /**
     * @return fvPDO
     * @throws Exception
     */
    public static function pdo(){
        static $pdo;

        if( empty( $pdo) ){
            try{
                $time = microtime(true);
                $pdo = new fvPDO( fvSite::config()->get( "database.dsn" ),
                    fvSite::config()->get( "database.user" ),
                    fvSite::config()->get( "database.pass" ) );

                if( defined("FV_PROFILE") && FV_PROFILE ){
                    Profile::addQuery("connection time", microtime(true) - $time, "pQ");
                }
            } catch ( Exception $e ){
                throw new Exception( "Couldn't connect to database: " .$e->getMessage() );
            }
        }

        return $pdo;
    }

    /**
     * @return fvConfig
     */
    public static function config(){
        static $config;

        if( empty($config) ){
            $config = new fvConfig();
            $config->loadFromFolder("configs");

            if( ! is_null($_SERVER['HTTP_HOST']) )
                $config->loadFromFolder("configs/hosts/{$_SERVER['HTTP_HOST']}");

            $config->loadFromFolder("configs/servers/" . gethostname());
        }

        return $config;
    }

    /**
     * @return fvApp
     * @throws Exception
     */
    public static function app() {
        if( !isset(self::$app) )
            throw new Exception("App is not initialized yet.");

        return self::$app;
    }

    /**
     * @return fvDictionary
     * @throws Exception
     */
    public static function dictionary(){
        static $dictionary = array();

        $key = null;
        if( isset( self::$app ) ){
            $key = self::$app->getName();
        }

        if( empty( $dictionary[$key] ) ){
            $class = "Dictionary_" . ucfirst( self::config()->get("dictionary.class", "raw") );
            if( !class_exists( $class ) ){
                throw new Exception("Dictionary class {$class} not exists");
            }

            $dictionary[$key] = new $class;

            if( ! $dictionary[$key] instanceof fvDictionary ){
                throw new Exception("Dictionary class {$class} must be instanceof fvDictionary");
            }
        }

        return $dictionary[$key];
    }

    /**
     * @return fvSession
     */
    public static function session () {
        static $session;

        if( empty($session) ){
            $class = "Session_" . ucfirst( self::config()->get("session.class", "BuiltIn") );
            $params = self::config()->get("session.params", null);
            $session = new $class( $params );
        }

        return $session;
    }
}