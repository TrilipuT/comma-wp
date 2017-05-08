<?php

/**
 * Created by cah4a.
 * Time: 17:20
 * Date: 09.10.13
 */
class fvMailer
{

    private $to = [ ];

    private $bcc = [ ];

    /** @var Strings */
    private $subject;

    /** @var Strings */
    private $textBody;

    /** @var Strings */
    private $htmlBody;

    const TYPE_NORMAL = 1;

    public function send()
    {
        $mailer = $this->newMailer();

        foreach( $this->getTo() as $email => $name ){
            $mailer->addAddress( $email, $name );
        }

        foreach( $this->getBcc() as $email => $name ){
            $mailer->addBCC( $email, $name );
        }

        $mailer->Subject = $this->getSubject();

        $html = $this->getHtmlBody();
        $text = $this->getTextBody();

        if( !empty($html) ){
            $mailer->IsHTML();

            $mailer->msgHTML( $this->getHtmlBody() );

            if( !empty($text) ){
                $mailer->AltBody = $text;
            }
        }
        else {
            $mailer->Body = $text;
        }

        $mailer->send();
    }

    /** @return \PHPMailer */
    private function newMailer()
    {
        $mailer = new PHPMailer( true );

        $mailer->CharSet = "utf-8";
        $mailer->SetLanguage( "ru" );

        $mailer->SingleTo = true;

        if( fvSite::config()->get( "mailer.smtp", true ) ){
            $mailer->IsSMTP();
        }

        $mailer->Host = fvSite::config()->get( "mailer.host", "localhost" );
        $mailer->Port = fvSite::config()->get( "mailer.port", 25 );

        if( fvSite::config()->get( "mailer.smtpAuth", true ) ){
            $mailer->SMTPAuth = true;
        }
        if( fvSite::config()->get( "mailer.smtpSecure", false ) ){
            $mailer->SMTPSecure = fvSite::config()->get( "mailer.smtpSecure" );
        }
        $mailer->Username = fvSite::config()->get( "mailer.username" );
        $mailer->Password = fvSite::config()->get( "mailer.password" );

        $mailer->From = fvSite::config()->get( "mailer.from" );
        $mailer->FromName = fvSite::config()->get( "mailer.fromName" );

        return $mailer;
    }

    /**
     * @param Strings $htmlBody
     *
     * @return $this
     */
    public function setHtmlBody( $htmlBody )
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param Strings $subject
     *
     * @return $this
     */
    public function setSubject( $subject )
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param Strings $textBody
     *
     * @return $this
     */
    public function setTextBody( $textBody )
    {
        $this->textBody = $textBody;
        return $this;
    }

	/**
	 * @return Strings
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

	/**
	 * @param Strings $email
	 * @param Strings $name
     *
     * @return $this
     */
    public function addTo( $email, $name = "" )
    {
        $this->to[$email] = $name;
        return $this;
    }

	/**
     * @param Strings $email
	 * @param Strings $name
     *
     * @return $this
     */
    public function addBccTo( $email, $name = "" )
    {
        $this->bcc[$email] = $name;
        return $this;
    }

    /**
     * @return $this
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    public function clearBcc()
    {
        $this->bcc = array();
        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    public function clearTo()
    {
        $this->to = array();
        return $this;
    }

}