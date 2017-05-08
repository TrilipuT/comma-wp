<?php

class Component_Mail extends fvComponent {

    public function getComponentName(){
        return "mail";
    }

    protected function mailer(){
        if( !isset($this->mailer) ){
            $this->mailer = new fvMailer();
        }

        return $this->mailer;
    }

    public function setSubject( $subject ){
        $this->mailer()->setSubject( $subject );
        return $this;
    }

    public function clearTo(){
        $this->mailer()->clearTo();
        return $this;
    }

    public function addTo( $email, $name = "" ){
        $this->mailer()->addTo( $email, $name );
        return $this;
    }

    public function clearBcc(){
        $this->mailer()->clearBcc();
        return $this;
    }

    public function addBccTo( $email, $name = "" ){
        $this->mailer()->addBccTo( $email, $name );
        return $this;
    }

    public function send(){
        $this->mailer()->setHtmlBody( (string)$this );

        $this->mailer()->send();

        return true;
    }

}