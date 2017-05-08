<?php

abstract class fvFieldCollection {

    /**
     *
     * @var fvField[] $_fields
     */
    protected $_fields = Array( );

    protected function updateFields( array $schema ) {
        $function = create_function( '$matches', 'return "_" . strtoupper($matches[1]);' );

        $new_fields = array( );

        foreach ( $schema as $name => $fieldSchema ) {
            if ( isset( $this->_fields[ $name ] ) ) {
                $this->_fields[ $name ]->updateSchema( $fieldSchema );
            }
            else {
                $type = preg_replace_callback( "/_(\w)/", $function, ucfirst( $fieldSchema[ 'type' ] ) );
                $className = 'Field_' . $type;
                //$this->_fields[$name] = new $className($fieldSchema, $name);
                $new_fields[ $name ] = new $className( $fieldSchema, $name );
            }
        }

        $this->_fields = array_merge_recursive( $new_fields, $this->_fields );
    }

    public function __get( $name ) {
        if ( !isset( $this->_fields[ $name ] ) ) {
            throw new Field_Exception( "Trying to get field '{$name}' wich does not implement in schema." );
        }

        return $this->_fields[ $name ];
    }

    public function __set( $name, $value ) {
        if ( !isset( $this->_fields[ $name ] ) ) {
            $this->_fields[ $name ] = new Field_Heap(array(), $name);
            //throw new EFieldError( "Trying to set field '{$name}' wich does not implement in schema." );
        }

        $this->_fields[ $name ]->set( $value );
    }

    function getFieldList() {
        return array_keys( $this->_fields );
    }

    /**
     * @param null $type
     * @param null $parameter
     * @return array|fvField[]
     */
    public function getFields( $type = null ) {
        if ( is_null($type) ) {
            return $this->_fields;
        }

        $result = Array( );

        foreach ( $this->_fields as $keyName => $field ) {
            if ( is_a( $field, $type ) )
                $result[ $keyName ] = $field;
        }

        return $result;
    }

    function isValid() {
        foreach ( $this->getFields() as $field ) {
            if ( !$field->isValid() )
                return false;
        }

        return true;
    }

    /**
     * Fill fields by array (fieldName => fieldValue)
     * @param Strings $map
     */
    function hydrate( array $map ) {
        try {
            if ( !is_array( $map ) )
                throw new Exception( "Can't create object from non array" );
            foreach ( $map as $field => $value ) {
                if ( !isset( $this->_fields[ $field ] ) ) {
                    $this->_fields[ $field ] = new Field_Heap(array(), $field);
                }

                $this->_fields[ $field ]->set( $value );
            }
        }
        catch ( Field_Exception $e ) {
            throw new Exception( "Field {$field} throw error: " . $e->getMessage() );
        }
    }

    function toHash() {
        $result = array( );
        foreach ( $this->_fields as $name => $field ) {
            $result[ $name ] = $field->get();
        }

        return $result;
    }

    function hasField( $fieldName ) {
        return isset( $this->_fields[ $fieldName ] );
    }

    function __clone() {
        foreach ( $this->_fields as &$field ) {
            $field = clone $field;
        }
    }

    function setChanged( $value ) {
        foreach ( $this->_fields as $field )
            $field->setChanged( $value );
    }

}
