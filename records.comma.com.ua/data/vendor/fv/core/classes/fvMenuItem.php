<?php

class fvMenuItem {

    const ACTIVATION_TYPE_CHECK_URI = "checkUri";
    const ACTIVATION_TYPE_ROUTE = "route";
    const ACTIVATION_TYPE_CONTROLLER = "controller";

    private $isCurrent = null;
    private $weight = null;
    private $icon = null;
    private $activationType = null;
    private $title = null;
    private $options = [];

    /** @var fvLink */
    private $link;

    /**
     * @param fvRoute|fvLink|Strings $link
     */
    function __construct( $link, $title = null, $linkParams = null ){
        if( $link instanceof fvRoute ){
            $link = $link->getLink();
        }

        if( is_string($link) ){
            $link = fvLink::build($link);
        }

        if( ! $link instanceof fvLink ){
            throw new Exception( sprintf("Can't create link from %s", is_object($link) ? get_class($link) . " class" : gettype($link) ) );
        }

        if( ! is_null($linkParams) ){
            $link->setParams( $linkParams );
        }

        $this->link = $link;
        $this->title = $title;
    }

    function getActivationType(){
        $route = $this->link->getRoute();

        if( $this->activationType === null ){
            $this->activationType = $route->getParam( "menu.activateType", null );

            if( $this->activationType === null ){
                if( count( $route->getRouteParams() ) > 0 ){
                    $this->activationType = self::ACTIVATION_TYPE_CHECK_URI;
                }
            }
        }

        return $this->activationType;
    }

    function getLink(){
        return $this->link;
    }

    function setActivationType( $type = null ){
        $this->activationType = $type;
        return $this;
    }

    function path(){
        return $this->link->generateUrl();
    }

    function title(){
        return $this->title;
    }

    public function setWeight( $weight ){
        $this->weight = (int)$weight;

        return $this;
    }

    public function getWeight(){
        if( !is_null( $this->weight ) ){
            return $this->weight;
        }

        return $this->link->getRoute()->getParam( "menu.weight", 200 );
    }

    public function isCurrent(){
        if( !is_null( $this->isCurrent ) ){
            return $this->isCurrent;
        }

        $route = $this->link->getRoute();

        switch( $this->getActivationType() ){
            case self::ACTIVATION_TYPE_CHECK_URI:
                return $this->path() == fvSite::app()->getRequest()->getUri();
            case self::ACTIVATION_TYPE_ROUTE:
                if( ! fvSite::app()->getRouter()->hasCurrentRoute() )
                    return false;

                return $route == $this->getCurrentRoute();
            case self::ACTIVATION_TYPE_CONTROLLER:
            default:
                if( ! fvSite::app()->getRouter()->hasCurrentRoute() )
                    return false;

                return $route->getController() == $this->getCurrentRoute()->getController();
        }
    }

    final protected function getCurrentRoute(){
        return fvSite::app()->getRouter()->getCurrentRoute();
    }

    public function setCurrent( $bool ){
        $this->isCurrent = (bool)$bool;

        return $this;
    }

    /**
     * @param Strings $icon
     *
     * @return $this
     */
    public function setIcon( $icon ){
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getIcon(){
        if( is_null($this->icon) )
            return "file";
        
        return $this->icon;
    }

    public function setTitle( $title ){
        $this->title = $title;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions( array $options )
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param Strings $name
     *
     * @return $this
     */
    public function getOption( $name, $default = null )
    {
        if( isset( $this->options[$name] ) ){
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}