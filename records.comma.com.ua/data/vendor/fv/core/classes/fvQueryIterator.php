<?php

/**
 * User: cah4a
 * Date: 11.10.2013
 * Time: 19:23
 *
 * Class for low memory usage while iterating.
 * If need more lowest memory usage use fvQueryCursor.
 *
 * Enjoy!
 *
 * Class fvQueryCursor
 */
class fvQueryIterator extends ArrayIterator {

    /** @var fvRoot */
    private $current;

    /** @var fvRootManager */
    private $manager;

    public function __construct( fvRootManager $manager, $array = array(), $flags = 0 ){
        $this->manager = $manager;
        parent::__construct( $array, $flags );
    }

    /**
     * @return fvRoot
     */
    public function current(){
        return $this->current = $this->manager->instantiate( parent::current() );
    }

    public function key(){
        return $this->current->getId();
    }

    /**
     * @return \fvRootManager
     */
    public function getManager(){
        return $this->manager;
    }


}
