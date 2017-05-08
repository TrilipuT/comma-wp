<?php

/**
 * Created by cah4a.
 * Time: 12:51
 * Date: 16.06.14
 */
abstract class fvCache
{
    /** @var Cache_Strategy */
    private $strategy;

    final public function get()
    {
        if( $this->isCacheExpired() ){
            $data = $this->load();
            $this->persist($data);
        }
        else {
            $data = $this->getStrategy()->load();
        }

        return $data;
    }

    abstract function load();

    abstract function getSpace();

    abstract function getKey();

    abstract function getMTime();

    private function isCacheExpired()
    {
        return $this->getMTime() > $this->getStrategy()->getCreationTime();
    }

    final protected function getStrategy()
    {
        if( is_null( $this->strategy ) ){
            $this->strategy = Cache_Strategy::make( $this->getSpace(), $this->getKey() );
        }

        return $this->strategy;
    }

    protected function persist($data){
        $strategy = $this->getStrategy();
        $strategy->persist($data);
    }
}