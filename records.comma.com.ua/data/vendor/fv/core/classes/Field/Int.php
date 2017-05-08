<?php

class Field_Int extends fvField {

    /**
     * Знаковое ли поле
     * @var bool $unsigned
     */
    protected $unsigned = true;

    function set( $value ){
        if( is_null($value) || $value === "" )
            parent::set( null );
        else
            parent::set( (int)$value );
    }

    function updateSchema( array $fieldSchema ) {
        if ( isset( $fieldSchema[ 'unsigned' ] ) )
            $this->unsigned = $fieldSchema[ 'unsigned' ];

        parent::updateSchema($fieldSchema);
    }
    
}