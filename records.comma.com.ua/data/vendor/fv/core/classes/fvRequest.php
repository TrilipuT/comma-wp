<?php


class fvRequest implements ArrayAccess {

    // THANK YOU CAPTAIN OBVIOUS !!! What would we do without you?!
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const HEAD = 'HEAD';

    const ERROR_FILE_SIZE = 1;
    const ERROR_FILE_TYPE = 2;
    const ERROR_SUCCESS = 0;

    protected $method;

    protected function __construct () {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     *
     * @staticvar self $instance
     * @return fvRequest
     */
    public static function getInstance() {
        static $instance;

        if (!isset($instance)) {
            $instance = new self;
        }

        return $instance;
    }

    public function getRequestMethod() {
        return $this->method;
    }

    public function getRequestParameter($name, $type = null, $default = null) {
        $names = explode("[", $name);
        $return = $_REQUEST;

        foreach( $names as $var ) {
            $var = rtrim($var, "]");

            if( !isset($return[$var]) ) {
                $return = $default;
                break;
            }

            $return = $return[$var];
        }

        if (!is_null($type)) {
            settype($return, $type);
        }

        return $return;
    }

    public function getGETParameters(){
        $return = $_GET;
        unset( $return['__lang'] );
        unset( $return['__url'] );
        return $return;
    }

    public function __get( $name ){
        return $this->getRequestParameter( $name );
    }

    public function isPost(){
        return $_SERVER['REQUEST_METHOD'] == self::POST;
    }

    public function putRequestParameter($name, $value) {
        $_REQUEST[$name] = $value;
    }

    public function hasRequestParameter($name) {
        return isset($_REQUEST[$name]);
    }

    public function isXmlHttpRequest() {
        if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) )
            return false;

        return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function uploadCount($fileName) {
        if (is_array(is_array($_FILES[$fileName]['tmp_name']))) return count($_FILES[$fileName]['tmp_name']);
        return 1;
    }

    public function isFileUpload($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['tmp_name']))
            return is_uploaded_file($_FILES[$fileName]['tmp_name'][$idx]);
        else return is_uploaded_file($_FILES[$fileName]['tmp_name']);
    }

    public function getUploadFileType ($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['type']))
            return $_FILES[$fileName]['type'][$idx];
        else return $_FILES[$fileName]['type'];
    }

    public function getUploadFileSize ($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['size']))
            return $_FILES[$fileName]['size'][$idx];
        else return $_FILES[$fileName]['size'];
    }

    public function getUploadFileData ($fileName, $idx = 0) {
        $realFileName = null;

        if (is_array($_FILES[$fileName]['name']))
            $realFileName = $_FILES[$fileName]['name'][$idx];
        else $realFileName = $_FILES[$fileName]['name'];

        return array(
            'file_name'	=> substr($realFileName, 0, strrpos($realFileName, ".")),
            'file_ext'	=> substr($realFileName, strrpos($realFileName, ".") + 1),
        );
    }

    public function getUploadTmpName($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['tmp_name']))
            return $_FILES[$fileName]['tmp_name'][$idx];
        else return $_FILES[$fileName]['tmp_name'];
    }

    public function getUploadFileName($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['name']))
            return $_FILES[$fileName]['name'][$idx];
        else return $_FILES[$fileName]['name'];
    }

    public function checkUploadFile($fileName, $idx = 0) {
        $file_type = $this->getUploadFileType($fileName, $idx);
        $file_data = $this->getUploadFileData($fileName, $idx);
        $file_size = $this->getUploadFileSize($fileName, $idx);

        if ($file_size > fvSite::config()->get("upload.allowed_filesize")) {
            return self::ERROR_FILE_SIZE;
        }

        if (is_array($allowed_types = fvSite::config()->get("upload.allowed_types"))) {
            if (!in_array(strtolower($file_type), $allowed_types))
                return self::ERROR_FILE_TYPE;
        } else if (is_array($allowed_ext = fvSite::config()->get("upload.allowed_ext"))) {
            if (!in_array(strtolower($file_data['file_ext']), $allowed_ext))
                return self::ERROR_FILE_TYPE;
        }

        return self::ERROR_SUCCESS;
    }

    public function saveUploadData($fileName, $destination, $idx = 0) {
        move_uploaded_file($this->getUploadTmpName($fileName, $idx), $destination);
    }

    public function parseQueryString($query, $param, $value = null) {
        list($url, $params) = explode('?', $query);

        $found = false;
        $result = '';
        foreach (explode("&", $params) as $paramPare) {
            if ($paramPare === "") continue;
            list ($_key, $_value) = explode('=', $paramPare);

            if ($_key == $param) {
                $found = true;
                if ($value !== null) {
                    $result .= (($result)?"&":"") . "$_key=$value";
                }
            } else {
                $result .= (($result)?"&":"") . "$_key=$_value";
            }
        }

        if (!$found) {
            $result .= (($result)?"&":"") . "$param=$value";
        }

        return $url . (($result)?"?$result":'');
    }

    public function getLanguage(){
        return $this->getRequestParameter( 'lang', 'Strings', 'ru' );
    }

    public function getRootUrl( $lang = false){
        return  fvSite::config()->get( 'dir_web_root' ) .
        ( ( $lang ) ? $lang : $this->getLanguage() ). "/";
    }

    public function getCurrentUrl(){
        return "/" . ltrim($_SERVER['REQUEST_URI'], "/");
    }

    public function getUri(){
        return "/" . ltrim(preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']), "/");
    }

    public function getReferer() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function getHost(){
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists( $offset ){
        return isset($_REQUEST[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet( $offset ){
        return $_REQUEST[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet( $offset, $value ){
        $_REQUEST[$offset] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset( $offset ){
        unset($_REQUEST[$offset]);
    }


}