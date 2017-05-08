<?php

class Strings {

    static function simplifyText( $text ) {
        if( ! self::checkUtf8($text) )
            $text = mb_convert_encoding($text, 'utf-8', 'cp-1251');

        if (function_exists('tidy_parse_string')) {
            $tidy = tidy_parse_string($text, array(), "utf8");
            $tidy->cleanRepair();
            $text = (string)$tidy;
        }

        $patterns = array(
            '/<script[^>]*?>.*?<\/script[^>]*?>/isu',
            '/<style[^>]*?>.*?<\/style[^>]*?>/isu',
            '/</iu',
            '/>/iu',
            '/\R/iu',
            '/[+&*:-]+/iu'
        );
        $replaces = array(
            ' ',
            ' ',
            ' <',
            '> ',
            ' ',
            ' ',
        );
        $text = preg_replace($patterns, $replaces, $text);
        $text = preg_replace("/\s+/iu", " ", strip_tags($text));
        $text = preg_replace("/\s+(\pP)/iu", "\\1", $text);
        $text = preg_replace("/[^\s\pP\pL\d]/iu", "",$text);

        return trim($text);
    }

    static function findWords( $body, Array $words ) {
        $body   = self::simplifyText( $body );
        $result = array();

        foreach( $words as $word ) {
            $word  = self::simplifyText(trim( $word ));
            $pword = preg_quote( $word, "/" );
            if( !empty( $word ) && preg_match( "/(^|\P{L}){$pword}(\P{L}|$)/iu", $body ) > 0 ) {
                $result[] = $word;
            }
        }

        return $result;
    }

    static function checkUtf8( $str ) {
        $len = strlen( $str );
        for( $i = 0 ; $i < $len ; $i++ ) {
            $c = ord( $str[$i] );
            if( $c > 128 ) {
                if( ( $c > 247 ) ) return false; elseif( $c > 239 ) $bytes = 4; elseif( $c > 223 ) $bytes = 3; elseif( $c > 191 ) $bytes = 2; else return false;
                if( ( $i + $bytes ) > $len ) return false;
                while( $bytes > 1 ) {
                    $i++;
                    $b = ord( $str[$i] );
                    if( $b < 128 || $b > 191 ) return false;
                    $bytes--;
                }
            }
        }

        return true;
    }

    static function humanReadableSeconds( $seconds, $depth = 1, $useReductions = true ){

        $depth--;

        /*** get the weeks ***/
        $weeks = intval(intval($seconds) / 3600 / 24 / 7);
        if( $weeks > 4 )
            return "more than month";

        if( $weeks > 0 ){
            $diff = $seconds - $weeks*3600*24*7;
            if( $useReductions )
                $string = $weeks . "w";
            else
                $string = $weeks . " week" . ($weeks > 1 ? 's' : '');

            if( intval($diff / 3600 / 24) > 0 && $depth )
                $string .= ( $useReductions ? " " : ", " ) . self::humanReadableSeconds( $diff, $depth );

            return $string;
        }

        /*** get the days ***/
        $days = intval(intval($seconds) / 3600 / 24);
        if( $days > 0 ) {
            $diff = $seconds - $days*3600*24;
            if( $useReductions )
                $string = $days . "d";
            else
                $string = $days . " day" . ($days > 1 ? 's' : '');

            if( intval($diff / 3600) > 0 && $depth )
                $string .= ( $useReductions ? " " : ", " ) . self::humanReadableSeconds( $diff, $depth );

            return $string;
        }

        /*** get the hours ***/
        $hours = intval(intval($seconds) / 3600);
        if( $hours > 0 ) {
            $diff = $seconds - $hours*3600;
            if( $useReductions )
                $string = $hours . "h";
            else
                $string = "$hours hour" . ($hours > 1 ? 's' : '');

            if( intval($diff / 60) > 0 && $depth )
                $string .= ( $useReductions ? " " : ", " ) . self::humanReadableSeconds( $diff, $depth );

            return $string;
        }

        /*** get the minutes ***/
        $minutes = bcmod((intval($seconds) / 60), 60);
        if( $minutes > 0 ){
            $diff = $seconds - $minutes*60;
            if( $useReductions )
                $string = $minutes . "m";
            else
                $string = "$minutes minute" . ($minutes > 1 ? 's' : '');

            if( intval($diff) > 0 && $depth )
                $string .= ( $useReductions ? " " : ", " ) . self::humanReadableSeconds( $diff, $depth );

            return $string;
        }

        /*** get the seconds ***/
        $seconds = bcmod(intval($seconds), 60);
        if( $useReductions )
            return $seconds . "s";
        else
            return "$seconds second" . ($seconds > 1 ? 's' : '');
    }

    static function parseException( Exception $e ){
        $link = '<link rel="stylesheet" href="/assets/fv/exception.css">';

        if( $e instanceof LogicException )
            return "{$link}<br/><div class='logic' id='exception'><h1>{$e->getMessage()}</h1></div>";

        if( !FV_DEBUG_MODE || !defined('FV_DEBUG_MODE') )
            return "{$link}<br/><div id='exception'><h1>An error occurred</h1><p>{$e->getMessage()}</p></div>";

        $trace = "";

        $file = preg_replace( "/\r|\n/m" , "\n", file_get_contents($e->getFile()));
        $file = explode("\n", $file);

        $preview = "";
        for( $i = $e->getLine()-10; $i < $e->getLine()+10; $i++ ){
            if( !isset($file[$i]) )
                continue;

            $padding = 0;
            $line = $file[$i];

            while( !empty($line) ){
                switch( $line[0] ){
                    case " ":
                        $line = substr($line, 1);
                        $padding++;
                        continue 2;
                    case "\t":
                        $line = substr($line, 1);
                        $padding+=4;
                        continue 2;
                }

                break;
            }
            $padding *= 7;
            $padding += 3;
            $padding = "{$padding}px";

            $line = str_replace(">", "&gt;", $line);
            $line = str_replace("<", "&lt;", $line);
            $line = str_replace(" ", "&thinsp;", $line);
            $line = str_replace("\t", "&thinsp;&thinsp;&thinsp;&thinsp;", $line);
            $line = preg_replace("/'[^']*'/", "<span class='string'>\\0</span>", $line);
            $line = preg_replace("/\"[^\"]*\"/", "<span class='string'>\\0</span>", $line);
            $line = preg_replace("/\\\$[\\w_]+/", "<span class='var'>\\0</span>", $line);
            $operators = array("foreach","as\\b","if","else","public","private","protected","function","abstract","class(?!=)","static",
                "interface","throw(?!s)","new", "case","switch","return","instanceof");
            $line = preg_replace("/" . implode("|", $operators) . "/", "<span class='operator'>\\0</span>", $line);
            $line = preg_replace("/(\\/\\/|#|\\*|\\/\\*).*$/", "<span class='comment'>\\0</span>", $line);

            if( $i+1 == $e->getLine() )
                $preview .= "<div class='line error' style='padding-left: {$padding}'><span class='linenumber'>{$i}.</span> {$line} </div>";
            else
                $preview .= "<div class='line' style='padding-left: {$padding}'><span class='linenumber'>{$i}.</span> {$line} </div>";
        }
        $preview = "<div id='preview'><div><div>{$preview}</div></div></div>";

        foreach( $e->getTrace() as $i => $line ){
            if( !isset($line['file']) )
                $line['file'] = "";
            if( !isset($line['line']) )
                $line['line'] = "";
            foreach( $line['args'] as &$arg ){
                if( is_null($arg) )
                    $arg = "null";
                if( is_string($arg) )
                    $arg = "\"{$arg}\"";
                if( is_array($arg) )
                    $arg = '<a title="' . str_replace( '"', '&quote;', print_r($arg, true) ) . '" onclick="javascript: alert(this.getAttribute(\'title\'))">Array</a>';
                if( is_object($arg) )
                    $arg = '<a title="' . str_replace( '"', '&quote;', print_r($arg, true) ) . '" onclick="javascript: alert(this.getAttribute(\'title\'))">'.get_class($arg).'</a>';
            }
            $args = implode(", ", $line['args']);

            if( !empty($line['class']) )
                $function = "{$line['class']}&#8203;<nobr>{$line['type']}</nobr>&#8203;{$line['function']}&#8203;({$args})";
            else
                $function = "{$line['function']}&#8203;({$args})";

            $pwd = preg_replace( "/data$/", "", getcwd() );
            $file = str_replace($pwd, "", $line['file']);
            $file = ltrim(str_replace("\\", "/", $file), "/");
            $file = str_replace("/", "/&#8203;", $file);
            $trace .= "<tr><td>{$i}</td><td>{$function}</td><td><a class='file' href='file://{$line['file']}'>{$file}</a></td><td>{$line['line']}</td></tr>";
        }
        $trace = "<div id='trace_table'><table><thead><tr><th></th><th>Function</th><th>File</th></tr><tr></tr></thead><tbody>{$trace}</tbody></table></div>";

        return "{$link}<br/><div id='exception'><h1>". get_class($e) ."</h1><p>{$e->getMessage()}</p> <a><label for='trace'>show/hide technical information</label></a> <input type='checkbox' class='ez-hide' id='trace'> <div>{$preview} {$trace}</div></div>";
    }

    static function getHostByUrl( $Address ) {
        $parseUrl = parse_url(trim($Address));
        return trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
    }

    /**
     * @param Strings $str
     * @param bool $capitalise_first_char
     *
     * @return Strings
     */
    public static function toCamelCase( $str, $capitalise_first_char = false ) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * @param Strings $str
     *
     * @return Strings
     */
    public static function fromCamelCase( $str, $delim = "_" ) {
        return preg_replace_callback('/([A-Z])/', function( $c ) use ( $delim ){
            return $delim . strtolower($c[1]);
        }, lcfirst($str));
    }

    public static function hashPassword( $password, $salt = false ){
        if( ! $salt )
            $salt = '$6$rounds=5000$' . uniqid(null, true) . "$";

        return crypt($password, $salt );
    }

    public static function checkPassword( $password, $currentPasswordHash ){
        $hash = self::hashPassword( $password, $currentPasswordHash );
        $length = max( strlen($hash), strlen($currentPasswordHash) );
        $diff = 0;
        for( $i = 0; $i < $length; $i ++ ){
            if( isset($hash[$i]) && isset($currentPasswordHash[$i]) )
                $diff += ord($hash[$i]) - ord($currentPasswordHash[$i]);
            else
                $diff++;
        }
        return $diff == 0;
    }

    public static function pluralForm( $noun ){
        $exceptions = array(
            "man" => "men",
            "woman" => "women",
            "mouse" => "mice",
            "tooth" => "teeth",
            "goose" => "geese",
            "foot" => "feet",
            "child" => "children",
            "ox" => "oxen",
            "fish" => "fish",
            "sheep" => "sheep",
            "deer" => "deer",
            "swine" => "swine",
        );

        if( isset($exceptions[$noun]) )
            return $exceptions[$noun];

        if( substr($noun, -1) == "f" )
            return substr($noun, 0, -1) . "ves";

        if( substr($noun, -2) == "ef" )
            return substr($noun, 0, -2) . "ves";

        if(
            substr($noun, -1) == "o" ||
            substr($noun, -1) == "s" ||
            substr($noun, -1) == "x" ||
            substr($noun, -2) == "ch" ||
            substr($noun, -2) == "sh"
        )
            return $noun . "es";

        if( substr($noun, -1) == "y" && strpos("eyuioa", substr($noun, -2, 1)) === false )
            return substr($noun, 0, -1) . "ies";

        return $noun . "s";
    }

}
