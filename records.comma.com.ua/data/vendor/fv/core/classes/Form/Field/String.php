<?php

class Form_Field_String extends Form_Field {

    /** @var Strings */
    private $type;

    /** @var bool */
    private $placeholder = false;

    function __construct ( $type = "text", array $validators = array() ) {
        $this->setType( $type );
        $this->addValidators( $validators );
    }

    /**
     * @param Strings $type
     *
     * @return $this
     */
    public function setType ($type) {
        $this->type = (string)$type;
        $this->view()->type = (string)$type;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getType () {
        return $this->type;
    }

    /**
     * @param Strings $placeholder
     *
     * @return $this
     */
    public function showPlaceholder( $placeholder = true ) {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getPlaceholder() {
        if( ! $this->placeholder )
            return null;

        $key = $this->getForm()->getName() . ".placeholders." . $this->getKey();
        if( is_string($this->placeholder) ){
            $key .= "." . $this->placeholder;
        }
        return $this->getForm()->getDictionary()->translate($key);
    }

}