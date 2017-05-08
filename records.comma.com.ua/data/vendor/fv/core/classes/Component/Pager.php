<?php


class Component_Pager extends Component_Extended implements ArrayAccess, Iterator, Countable{

    protected $manager;
    protected $query;
    protected $objects;
    protected $pageCount;
    protected $objectsCount;
    protected $perPage = 10;
    protected $currentPage = null;
    protected $paramName = 'page';
    protected $executed = false;

    /** @var fvLink */
    protected $link = false;

    function getComponentName(){ return 'component'; }

    /**
     * @param fvRootManager|fvQuery|fvRoot|Strings $obj
     * @param Strings $alias
     */
    function __construct( $obj, $alias = 'root' ){
        $router = fvSite::app()->getRouter();
        $this->link = $router->getCurrentLink();
        $this->link->setParams($_GET);

        if( $obj instanceof fvRootManager ){
            $this->manager = $obj;
            $this->query = new fvQuery( $obj, $alias );
            return;
        }

        if( $obj instanceof fvQuery ){
            $this->query = $obj;
            $this->manager = $obj->getRootManager();
            return;
        }

        if( $obj instanceof fvRoot ){
            $this->manager = $obj->getManager();
            $this->query = new fvQuery( $this->manager, $alias );
            return;
        }

        if( is_string($obj) ){
            $this->manager = fvManagersPool::get($obj);
            $this->query = new fvQuery( $this->manager, $alias );
            return;
        }

        throw new Exception("Object param must be instance of fvRootManager or fvRoot or fvQuery or string class name");
    }

    function query(){
        return $this->query;
    }

    function execute(){
        $offset = ($this->getCurrentPage() - 1) * $this->perPage;
        $count = $this->objectsCount = $this->query()->getCount();

        if( $offset < 0 || ($offset > 0 && $offset > $count - 1) ){
            throw new Error_PageNotFound;
        }

        $this->setPageCount( ceil( $count / $this->getPerPage() ) );
        $this->objects = $this->query()->limit( $this->getPerPage(), $offset )->fetchAll();

        $this->prerender();

        $this->executed = true;

        return $this;
    }

    public function isEmpty(){
        return count( $this->objects ) == 0;
    }

    public function setParamName( $getParameter ){
        $this->paramName = (string)$getParameter;

        return $this;
    }

    public function getParamName(){
        return $this->paramName;
    }

    public function getCurrentPage(){
        if( $this->currentPage === null ){
            $this->currentPage = fvSite::app()->getRouter()->getUriParam( $this->getParamName() );

            if( $this->currentPage === null ){
                $this->currentPage = fvRequest::getInstance()->getRequestParameter( $this->getParamName(), 'int', 1 );
            }
        }

        return $this->currentPage;
    }

    public function setCurrentPage( $page ){
        $this->currentPage = $page;
        return $this;
    }

    public function getObjectsCount(){
        if( is_null( $this->objectsCount ) ){
            throw new Exception("Pager must be executed before can in getObjectCount method");
        }

        return $this->objectsCount;
    }

    public function setPerPage( $perPage ){
        $this->perPage = $perPage;

        return $this;
    }

    public function getPerPage(){
        return $this->perPage;
    }

    public function hasPaginate(){
        return ( $this->getPageCount() > 1 );
    }

    public function getPagesLinks(){
        $pages = array();
        for( $i = 1; $i <= $this->getPageCount(); $i++ ){
            if( ( $this->getPageCount() < 11 ) || ( $i <= 3 ) || ( ( $this->getPageCount() - $i ) < 3 ) || ( abs( $i - $this->getCurrentPage() ) < 3 )
            ){
                $pages[] = $i;
            }
        }
        return $pages;
    }

    public function getPageHref( $page ){
        return $this->getLink()->setParam( $this->getParamName(), $page );
    }

    public function getLink(){
        return $this->link;
    }

    public function setPageCount( $pageCount ){
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getPageCount(){
        return $this->pageCount;
    }

    public function getObjects(){
        return $this->objects;
    }

    function offsetExists( $offset ){
        return isset( $this->objects[$offset] );
    }

    function offsetGet( $offset ){
        return $this->objects[$offset];
    }

    function offsetUnset( $offset ){
        unset( $this->objects[$offset] );
    }

    function offsetSet( $offset, $newValue ){
        $this->objects[$offset] = $newValue;
    }

    public function rewind(){
        if( ! $this->executed )
            $this->execute();

        reset( $this->objects );
    }

    public function current(){
        $var = current( $this->objects );

        return $var;
    }

    public function key(){
        $var = key( $this->objects );

        return $var;
    }

    public function next(){
        $var = next( $this->objects );

        return $var;
    }

    public function valid(){
        $var = $this->current() !== false;

        return $var;
    }

    public function getManager(){
        return $this->manager;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     *       The return value is cast to an integer.
     */
    public function count(){
        return count( $this->objects );
    }
}
