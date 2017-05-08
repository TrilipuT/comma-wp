<?php

abstract class fvController extends fvComponent {

    /** @var  fvLayout */
    private $layout = null;

    /** @var Strings methodName */
    private $action = null;

    /** @var bool */
    private $useLayout = null;

    final function getComponentName() {
        return 'controller';
    }

    protected function init(){}

    public function resolveArguments( $method, array $params ){
        $reflection = new ReflectionClass( $this );
        $reflectionMethod = $reflection->getMethod( $method );
        $resultedParams = array();
        foreach( $reflectionMethod->getParameters() as $reflectionParam ){
            $name = $reflectionParam->getName();

            if( isset( $params[$name] ) ){
                $resultedParams[$name] = $params[$name];
                continue;
            }

            if( !is_null( $this->getRequest()->{$name} ) ){
                $resultedParams[$name] = $this->getRequest()->{$name};
                continue;
            }

            if( $reflectionParam->isDefaultValueAvailable() ){
                $resultedParams[$name] = $reflectionParam->getDefaultValue();
                continue;
            }

            throw new Error_PageNotFound;
        }

        return $resultedParams;
    }

    public function resolveTemplateName(){

    }

    final public function handle( $action, $params = array() ){
        $this->setActionName( $action );
        $this->resolveTemplateName();

        if( $this->init() === false ){
            return "";
        }

        $method = $action . "Action";
        if( ! method_exists( $this, $method ) )
            throw new Exception("No '$method' action found in " . get_class($this));

        ob_start();
        $methodParams = $this->resolveArguments( $method, $params );
        $methodResult = call_user_func_array( array($this, $method), $methodParams );

        $out = ob_get_clean();
        if( $methodResult !== null ){
            if( $methodResult instanceof stdClass ){
                $this->getResponse()->setHeader('Result', json_encode($methodResult));
                $result = $out;
            } else
                $result = $out . $methodResult;
        } else {
            $result = $out . $this;
        }

        if( ! $this->isUseLayout() ){
            return $result;
        }

        $layout = $this->getLayout();
        $out = ob_get_clean();

        if( ! $layout instanceof fvLayout )
            return $out . $this;

        $layout->setBody($result);

        return $layout;
    }

    /**
     * @return fvLayout|null
     */
    public function getLayout() {
        return $this->layout;
    }

    final public function setLayout( $name ){
        if( $name instanceof fvLayout ){
            $this->layout = $name;
            return $this;
        }

        if( is_null($name) ){
            $this->layout = null;
            return $this;
        }

        if( is_string($name) ){
            if( ! class_exists($name) ){
                if( substr($name, 0, strlen("Layout_")) != "Layout_" )
                    $name = "Layout_" . ucfirst($name);
            }

            if( ! class_exists($name) )
                throw new Exception("Layout class '{$name}' not exist");

            return $this->setLayout( new $name );
        }

        throw new Exception("Layout must be instanceof fvLayout or string with class name which extends fvLayout");
    }

    /**
     * @return fvRequest
     */
    final public function getRequest() {
        return fvRequest::getInstance();
    }

    /**
     * @return fvResponse
     */
    protected function getResponse() {
        return fvResponse::getInstance();
    }

    /**
     * @param boolean $useLayout
     * @return $this
     */
    public function useLayout( $useLayout ){
        $this->useLayout = (bool)$useLayout;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isUseLayout(){
        if( $this->useLayout === null )
            return ! $this->getRequest()->isXmlHttpRequest();

        return $this->useLayout;
    }

    /**
     * @param Strings $action
     *
     * @return $this
     */
    public function setActionName( $action ){
        $this->action = $action;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getActionName(){
        return $this->action;
    }


    public function redirect( $link, $params = array() ){
        $this->getResponse()->redirect( $this->path( $link, $params ) );
        $this->useLayout(false);
        return true;
    }

    public function path( $link, $params = array(), $absolute = false ){
        if( strpos( $link, ":" ) === false ){
            $link = fvLink::controllerTransformation( get_class($this) ) . ":" . $link;
        }
        return fvUrlGenerator::get( $link, $params, $absolute );
    }

}