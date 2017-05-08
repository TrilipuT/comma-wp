<?php

class Profile {
    const PROFILER_MARK = "profiler";

    public static $queries = array( );
    public static $totalTime = array();

    public static $startTime;
    public static $endTime;

    static function isEnabled(){
        return fvRequest::getInstance()->getRequestParameter( self::PROFILER_MARK, "bool", false );
    }

    static function addQuery( $query, $time, $type = "Q" ) {
        if( ! self::isEnabled() )
            return;

        self::$queries[ $time . rand(1000,10000) ] = array( "query" => preg_replace( "/\r?\n?\s+/", " ", $query ), "time" => $time, "type" => $type );
        self::$totalTime[$type] += $time;
    }

    static function show() {
        if( ! self::isEnabled() )
            return;

        self::end();

        print '<style>
        #profile-output { margin: 40px auto; padding: 30px; background: white; border-radius: 3px; }
        #profile-output th { text-align: center; }
        #profile-output td { padding: 5px; }
        #profile-output tr:nth-child(even) { background: rgba(0,0,0,.05) }
        </style><div id="profile-output">';

        ksort( self::$queries );
        self::$queries = array_reverse( self::$queries );

        print "Total time: " . sprintf("%.5s", self::$endTime - self::$startTime) . "<br/>";

        $memory =  memory_get_usage() / 1024 / 1024;
        print "Memory usage: " . sprintf("%.1fM", $memory) . "<br/>";
        $memory =  memory_get_peak_usage() / 1024 / 1024;
        print "Memory peak usage: " . sprintf("%.1fM", $memory) . "<br/><br/>";

        print "Total query time: <br/>";

        foreach( self::$totalTime as $type => $time ){
            print "{$type}: " . sprintf("%.5s", $time);
        }

        print "<br/><br/><br/><table><tr><th style=''>TYPE</th><th>COMMAND</th><th>TIME</th></tr>";
        foreach ( self::$queries as $q ) {
            $time = sprintf("%01.5f", $q["time"]);
            print "<tr><td>{$q["type"]}</td><td>{$q["query"]}</td><td>{$time}</td></tr>";
        }
        print "</table></div>";
    }

    public static function startTime( $time ){
        self::$startTime = $time;
    }

    public static function end(){
        self::$endTime = microtime(true);
    }

}