<?php

class Form_Field_Select extends Form_Field {

    private $multiple = false;

    /** @var array */
    private $values = array();

    /** @var array */
    private $nullable = false;

    private $placeholder = false;

    function __construct ( array $values = array(), array $validators = array() ) {
        $this->setValues( $values );
        $this->addValidators( $validators );
    }

    public function setMultiple( $multiple ){
        $this->multiple = (bool)$multiple;
        return $this;
    }

    public function isMultiple(){
        return $this->multiple;
    }

    public function getName(){
        return parent::getName() . ( $this->isMultiple() ? "[]" : "" );
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

    public function addValue( $key, $value ){
        $this->values[$key] = $value;
        return $this;
    }

    public function addValues( array $values ){
        foreach( $values as $key => $value ){
            $this->addValue($key, $value);
        }
        return $this;
    }

    public function clearValues(){
        $this->values = array();
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues( array $values ){
        $this->clearValues();
        return $this->addValues( $values );
    }

    /**
     * @return array
     */
    public function getValues(){
        return $this->values;
    }

    public function isSelected( $key ){
        if( $this->isMultiple() && is_array($this->getValue()) ){
            return in_array( $key, $this->getValue() );
        }

        return $this->getValue() == $key;
    }

    public function setNullable( $nullable = true ){
        $this->nullable = $nullable;
        return $this;
    }

    public function isNullable(){
        return $this->nullable;
    }

    /**
     * @param Strings $placeholder
     *
     * @return $this
     */
    public function showPlaceholder( $placeholder = true ) {
        $this->placeholder = (bool)$placeholder;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getPlaceholder() {
        if( ! $this->placeholder )
            return null;

        $key = $this->getForm()->getName() . ".placeholders." . $this->getKey();
        return $this->getForm()->getDictionary()->translate($key);
    }

}