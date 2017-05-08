<?php

class fvResponse {
    protected $_headers = array();
    protected $_responseBody;
    protected $_statusText;
    protected $_status = 200;
    protected $_pragmaNoCache = false;

    protected function __construct() {
        $this->_headers = array();
        $this->_responseBody = '';
        $this->_statusText = array(
            '100' => 'Continue',
            '101' => 'Switching Protocols',
            '200' => 'OK',
            '201' => 'Created',
            '202' => 'Accepted',
            '203' => 'Non-Authoritative Information',
            '204' => 'No Content',
            '205' => 'Reset Content',
            '206' => 'Partial Content',
            '300' => 'Multiple Choices',
            '301' => 'Moved Permanently',
            '302' => 'Found',
            '303' => 'See Other',
            '304' => 'Not Modified',
            '305' => 'Use Proxy',
            '306' => '(Unused)',
            '307' => 'Temporary Redirect',
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '407' => 'Proxy Authentication Required',
            '408' => 'Request Timeout',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '414' => 'Request-URI Too Long',
            '415' => 'Unsupported Media Type',
            '416' => 'Requested Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Unavailable',
            '504' => 'Gateway Timeout',
            '505' => 'HTTP Version Not Supported',
        );
    }

    /**
     * @static
     * @return fvResponse
     */
    public static function getInstance() {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function setStatus($status) {
        $this->_status = $status;
        return $this;
    }

    public function setHeader ($header, $value) {
        $this->_headers[$header] = $value;
        return $this;
    }

    public function sendHeaders() {
        header("HTTP/1.0 " . $this->_status . " " . $this->_statusText[$this->_status]);
        header("Pragma: " . ($this->_pragmaNoCache ? 'no-cache' : '') );
        header('X-Powered-By: ');
        foreach ($this->_headers as $header => $value) {
            header("{$header}: {$value}");
        }
        return $this;
    }

    public function setPragma( $cache ){
        $this->_pragmaNoCache = (bool)$cache;
        return $this;
    }

    public function clearHeaders() {
        $this->_headers = array();
        return $this;
    }

    public function setResponseBody( $body ) {
        $this->_responseBody = $body;
        return $this;
    }

    public function getResponseBody() {
        return $this->_responseBody;
    }

    public function sendResponseBody() {
        echo $this->getResponseBody();
        return $this;
    }

    public function setFlash($message, $type, $trace = null) {
        $this->setHeader("actionmessage", json_encode(array('message' => $message, 'type' => $type, "trace" => $trace)));
        return $this;
    }

    public function send() {
        $this->sendHeaders();
        $this->sendResponseBody();
        return $this;
    }

    public function redirect( $redirect ){
        $this->setHeader("Location", $redirect);
        return $this;
    }

    public function redirectToLink( $link, $params = array() ){
        return $this->redirect( fvUrlGenerator::get($link, $params) );
    }

}
