<?php

abstract class fvSession {

    private $sessionStarted = false;

    abstract protected function getByKey( $key );

    abstract protected function setByKey( $key, $value );

    abstract protected function readOpen();

    abstract protected function writeClose();

    abstract protected function destroyClose();

    final public function __get( $key ){
        if( ! $this->isStarted() ){
            if( $this->isSessionCanExist() )
                $this->start();
            else
                return null;
        }

        return $this->getByKey( $key );
    }

    final public function __set( $key, $value ){
        $this->start();
        $this->setByKey( $key, $value );
    }

    final public function isStarted(){
        return $this->sessionStarted;
    }

    final public function start(){
        if( ! $this->isStarted() ){
            $this->sessionStarted = true;
            $this->readOpen();
        }
        return $this;
    }

    final public function stop(){
        if( $this->isStarted() ){
            $this->sessionStarted = false;
            $this->writeClose();
        }
        return $this;
    }

    final public function destroy(){
        if( $this->isStarted() ){
            $this->sessionStarted = false;
            $this->destroyClose();
        }
        return $this;
    }

    /**
     * Это нужно для того, чтобы не создавать новую сессию, если сессия прежде не была открыта.
     *
     * @return bool
     */
    public function isSessionCanExist(){
        return isset($_COOKIE[$this->getSessionKey()]);
    }

    public function getSessionKey(){
        return session_name();
    }

    /**
     * @return Admin
     */
    public function getAdmin(){
        if( ! $this->adminId ) return null;

        static $admin;

        if( empty($admin) ){
            $admin = Admin::getManager()->getByPk( $this->adminId );

            if( $admin instanceof Admin ){
                if( ! $admin->isActive->get() ){
                    $this->adminId = null;
                    $admin = null;
                }
            } else {
                $this->adminId = null;
            }
        }

        return $admin;
    }

    /**
     * @return User
     */
    public function getUser(){
        if( ! $this->userId ) return null;

        static $user;

        if( empty($user) ){
            $user = User::getManager()->getByPk( $this->userId );

            if( ! $user instanceof User ){
                $this->userId = null;
            }
        }

        return $user;
    }

}