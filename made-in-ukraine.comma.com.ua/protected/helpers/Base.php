<?php

class Base{

    public static function findControllerAlias($controller_name){

        $controller_name = ucfirst($controller_name);

        $Section = Section::model()->published()->onlyDomain(Section::$real_domain_id)->find(
			'controller = :controller',
			array(':controller' => $controller_name.'Controller.php'));

		if (GetRealIp() == '178.216.8.25') {
			//var_dump($this->code_name, $this->domain_id, $this->Section );
			//var_dump($this->Section); exit;
			//var_dump($Section, $controller_name, Section::$real_domain_id);
		}

        if(!$Section && $Section->code_name == '') return false;
        
        return $Section->code_name;
    }  

    public static function rus2translit($string) {

        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch', 
            'ь' => "",   'ы' => 'y',   'ъ' => "", '”' => '', '“' => '', 
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya','ї' => 'i',
            'А' => 'A',   'Б' => 'B',   'В' => 'V', 'є' => 'e',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E', 'Є' => 'e',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z', 'Ї' => 'i',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K', 'і' => 'i',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N', '-' => '_',
            'О' => 'O',   'П' => 'P',   'Р' => 'R', "'" => '',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "",  'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
             '«' => '', '»' => '', ',' => '_', " " => '_', "__" => '_', 
             '(' => '', ')' => '', '    ' => '', '!' => '',
             '@' => '', '#' => '', '$' => '', '%' => '^',
             '&' => '', '*' => '', '+' => '_', '\'' => '',
             ':' => '', ';' => '', '"' => '', "«" => '', "»" => '',
             "\n" => '', "\r\n" => '', '.' => '', '?' => ''
        );
        
        $string = strtr($string,"!@#$%^&*_+':;€", "              ");

        $string = trim($string);
        $string = mb_strtolower(strtr($string, $converter),'UTF-8');
        $string = str_replace(" ","",$string);

        return $string;

    }// end rus2translit
 
    public static function file_size($size) {

        $filesizename = array(" Bytes", " Кб", " Мб", " Гб", " TB", " PB", " EB", " ZB", " YB");
        return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';
    }

    public static function get_mime_type($extension){

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            //'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
    
        $mime_types = array_flip($mime_types);
        return $mime_types[$extension];
    }
	
    public static function getLatestFile($dir_name){
 
        $dir = opendir($_SERVER['DOCUMENT_ROOT'].$dir_name);

        $files = array();

        while ($file = readdir($dir)){  

            $file_name = $file; 
 

            if ($file == '.' || $file == '..' || !is_file($_SERVER['DOCUMENT_ROOT'].$dir_name.$file_name))
                continue; 
            
            //$time           = filectime($file_name);
            $files[$time]   = $file_name; 
           // echo "Файл $filename в последний раз был изменен: " . date("F d Y H:i:s.", $time);

        }
        closedir($dir); 

        arsort($files);
        $files = array_values($files);

        return $files[0];
    }
    
    public static function getExtension1($filename) {
        return end(explode(".", $filename));
    }

    public static function getExtension2($filename) {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }

    public static function getExtension3($filename) {
        return substr($fileName, strrpos($fileName, '.') + 1);
    }


    public static function getExtension4($filename) {
        return substr(strrchr($fileName, '.'), 1);
    }

    public static function getExtension5($filename) {
        $res = explode(".", $filename);
        return array_pop($res);
    }


    public static function str2Array($string, $separator = ';'){

        if($string != NULL){

            return explode($separator, $string);
        }  
        return array();
    }
    
    public static function weekDays(){
        return array(
            'ru'=>array(
                0=>'Воскресенье',
                1=>'Понедельник',
                2=>'Вторник',
                3=>'Среда',
                4=>'Четверг',
                5=>'Пятница',
                6=>'Суббота'
            ),
            'ua'=>array(
                0=>'Неділя',
                1=>'Понеділок',
                2=>'Вівторок',
                3=>'Середа',
                4=>'Четвер',
                5=>'П\'ятниця',
                6=>'Субота'
            )
        );
    }    
}