<?php

class Field_String extends fvField {

    const DEF_LENGTH=255;

    private $length;

    function updateSchema( array $fieldSchema ){
        if ( isset( $fieldSchema[ 'length' ] ) )
            $this->length = ( int ) $fieldSchema[ 'length' ];

        parent::updateSchema( $fieldSchema );
    }

    function getLength(){
        return $this->length;
    }

    function set( $value ) {
        if( is_string($value) ) {
            if( strlen( $value ) == 0 ) {
                parent::set( null );
                return;
            }
        }
        
        parent::set( $value );
    }

    function getSQlPart() {
        if (!$this->length) $this->length=self::DEF_LENGTH;
        $isNull = $this->nullable ? 'NULL' : 'NOT NULL';

        if (is_null($this->get())) {
            $default = $this->nullable ? 'DEFAULT NULL' : '';
        } else {
            $default = "DEFAULT '".$this->get()."'";
        }
        return  "varchar({$this->length}) $isNull $default";
    }
}