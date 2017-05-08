<?php

abstract class fvView {

    private $params = array();
    private $view;
    private $path;

    final public function __construct() {}

    abstract public function render();

    /**
     * @param $view
     * @return $this
     */
    public function setView($view) {
        $this->view = $view;
        return $this;
    }

    /**
     * @return Strings
     * @return $this
     */
    public function getView() {
        return $this->view;
    }

    public function assignParams( array $values ){
        foreach( $values as $name => $value ){
            $this->assignParam($name, $value);
        }
    }

    public function assignParam( $name, $value ){
        $this->params[$name] = $value;
        return $this;
    }

    public function getParam($name){
        return $this->params[$name];
    }

    public function getParams(){
        return $this->params;
    }

    /**
     * @param Strings $path
     *
     * @return $this
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * @return Strings
     */
    final public function getPath() {
        return $this->path;
    }

    function __set($name, $value) {
        $this->assignParam( $name, $value );
    }

    function __get($name) {
        return $this->getParam( $name );
    }

    abstract public function getExtension();


}