<?php

/**
 * User: cah4a
 * Date: 11.10.2013
 * Time: 20:18
 *
 * Class for very low memory usage while iterating.
 * Can't rewind at all, so be careful!
 *
 * Class fvQueryCursor
 */
class fvQueryCursor implements Iterator {

    /** @var fvRoot */
    private $current;

    /** @var fvRootManager */
    private $manager;

    public function __construct( fvRootManager $manager, PDOStatement $cursor ){
        $this->manager = $manager;
        $this->cursor = $cursor;
    }

    /**
     * @return fvRoot
     */
    public function current(){
        return $this->current;
    }

    public function key(){
        return $this->current->getId();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(){
        if( $row = $this->fetch() )
            $this->current = $this->manager->instantiate( $row );
        else
            $this->current = null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(){
        return !is_null( $this->current );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(){
        if( is_null( $this->current ) ){
            $this->next();
        } else
            throw new Exception("Rewind is not available for fvQueryCursor. Use fvQueryIterator instead of");
    }


    /**
     * @return \fvRootManager
     */
    public function getManager(){
        return $this->manager;
    }

    private function fetch(){
        return $this->cursor->fetch();
    }


}
