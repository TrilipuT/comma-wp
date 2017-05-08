<?php

class fvMenu extends fvComponent {

    private $items = array();
    private $sorted = false;
    private $weight = 0;

    public function getComponentName(){
        return "component";
    }

    function __construct(){
        $this->addItems( fvSite::config()->get( "menu", array() ) );

        /** @var fvRoute $route */
        foreach( fvSite::app()->getRouter()->getRoutes() as $route ){
            $name = $route->getParam("menu.name");
            $icon = $route->getParam("menu.icon");

            if( ! $name ){
                continue;
            }

            $this->addItem( $route->getController(), $name, $route, $icon );
        }

        $this->sort();
    }

    /**
     * @param $name
     * @param fvRoute|Strings $link
     * @return fvMenuItem
     */
    public function addItem( $key, $name, $link, $icon = null ){
        $names = explode( "//", $name );
        $currentName = array_pop( $names );

        $current = & $this->items;
        foreach( $names as $name ){
            $name = trim($name);
            if( !isset( $current[$name] ) ){
                $current[$name] = array( 'submenu' => array() );
            }

            $current = & $current[$name]['submenu'];
        }

        $current[$key]['item'] = $item = $this->createMenuItem( $link );
        $item->setTitle( $currentName );
        $item->setIcon( $icon );
        $item->setWeight( $this->incWeight() );
        $this->sorted = false;

        return $item;
    }

    public function addItems( array $array ){
        foreach( $array as $key => $item ){
            $icon = isset($item['icon']) ?  $item['icon'] : null;
            $menuItem = $this->addItem( $key, $item['name'], $item['link'], $icon );

            if( $menuItem ){
                if( isset( $item['weight'] ) ){
                    $menuItem->setWeight( $item['weight'] );
                }

                if( isset( $item['options'] ) ){
                    $menuItem->setOptions( $item["options"] );
                }
            }
        }

        return $this;
    }

    private function sortItems( $items ){
        uasort( $items, function ( $a, $b ){
            if( ! isset($a['item']) || ! isset($b['item']) )
                return true;

            /** @var fvMenuItem $aItem */
            $aItem = $a['item'];
            /** @var fvMenuItem $bItem */
            $bItem = $b['item'];

            return $aItem->getWeight() > $bItem->getWeight();
        } );

        foreach( $items as $key => $item ){
            if( isset( $item['submenu'] ) ){
                $item['submenu'] = $this->sortItems( $item['submenu'], false );
            }
        }

        return $items;
    }

    protected function sort(){
        if( ! $this->sorted ){
            $this->items = $this->sortItems( $this->items );
            $this->sorted = true;
        }

        return $this;
    }

    /**
     * @param $name
     * @return fvMenuItem
     */
    function getItem( $name ){
        $names = explode( "//", $name );
        $currentName = array_pop( $names );

        $current = $this->items;
        foreach( $names as $name ){
            if( !isset( $current[$name] ) ){
                return null;
            }

            $current = $current[$name]['submenu'];
        }

        return $current[$currentName]['item'];
    }

    function getItems(){
        return $this->sort()->items;
    }

    protected function createMenuItem( $connector ){
        if( is_string($connector) ){
            $link = fvLink::build($connector);
        }

        if( $connector instanceof fvRoute ){
            $link = new fvLink();
            $link
                ->setRoute( $connector )
                ->parseParams( $connector->getParam("menu.params", "") );
        }

        if( $connector instanceof fvLink )
            $link = $connector;

        if( empty($link) ){
            if( is_object($connector) ){
                throw new Exception("Can't create menu item from class " . get_class($connector));
            }

            throw new Exception("Can't create menu item from " . gettype($connector));
        }

        return $this->instantiateMenuItem( $link );
    }

    protected function instantiateMenuItem( $link ){
        return new fvMenuItem( $link );
    }

    protected function getByKey( $targetKey ){
        foreach( $this->getItems() as $key => $val ){

        }
    }

    private function incWeight(){
        return $this->weight++;
    }
}