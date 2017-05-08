<?php

class Field_Constraint extends Field_Virtual implements IteratorAggregate {

    protected $pk;
    protected $foreignEntityName;
    protected $cache = array();
    protected $refTableName;
    protected $foreignKey;
    protected $toSet;

    function __construct( array $fieldSchema, $key ){
        parent::__construct( $fieldSchema, $key );

        $this->foreignEntityName = $fieldSchema['entity'];

        if( $fieldSchema['foreign_key'] )
            $this->foreignKey = $fieldSchema['foreign_key'];
        else
            $this->foreignKey = lcfirst( $fieldSchema[ 'currentEntity' ] ) . 'Id';
    }

    public function getForeignEntityKey(){
        return $this->foreignKey;
    }

    private function getTableName(){
        $this->refTableName = fvManagersPool::get( $this->foreignEntityName )->getTableName();

        return $this->refTableName;
    }

    public function set( $value ){

    }

    public function get(){
        $cacheKey = md5( '' );

        if( !$this->pk )
            return array();

        if( !isset( $this->cache[$cacheKey] ) ){
            $this->cache[$cacheKey] = fvManagersPool::get( $this->foreignEntityName )
                                      ->select()
                                      ->where("{$this->foreignKey} = ?", $this->pk)
                                      ->fetchAll();
        }

        return $this->cache[$cacheKey];
    }

    function count(){
        return fvManagersPool::get( $this->foreignEntityName )
                ->select()
                ->where("{$this->foreignKey} = ?", $this->pk)
                ->getCount();
    }

    /** @return fvQuery */
    public function select( $expression = null ){
        return fvManagersPool::get( $this->foreignEntityName )->select($expression)
            ->where( array( $this->foreignKey => $this->pk) );
    }

    function setRootPk( $value ){
        $this->pk = $value;
    }

    public function getForeigners(){
        return fvManagersPool::get( $this->foreignEntityName )
            ->select()
            ->where( Array( $this->foreignKey => $this->pk ) )
            ->fetchAll();
    }

    public function getForeignEntityName() {
        return $this->foreignEntityName;
    }

    public function loadCache( $cacheKey, $entities ){
        $this->cache[ $cacheKey ] = $entities;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return $this->select()->getCursor();
    }


}