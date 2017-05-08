<?php

class Form_Field_RootSelect extends Form_Field_Select {

    function __construct( array $values = array(), array $validators = array() ){
        parent::__construct( $values, $validators );
    }

    public function setValues( array $values ){
        if( empty($values) )
            return parent::setValues( $values );

        $first = reset($values);

        if( ! $first instanceof fvRoot )
            return parent::setValues( $values );

        parent::setValues( self::toString( $values ) );

        return $this;
    }

    public static function toString( $values ){
        if( empty($values) )
            return [];

        $values = (array)$values;

        /** @var fvRoot $first */
        $first = reset($values);

        if( method_exists( $first, "__toString" ) ){
            $closure = function( $item ){
                return (string)$item;
            };
        } else {
            $fields = array("name", "title", "caption");
            $field = reset( array_intersect($fields, $first->getFieldList()) );

            if( ! $field ){
                $field = key( $first->getFields("Field_String") );
            }

            if( $field ){
                $closure = function( fvRoot $item ) use ( $field ){
                    return $item->{$field}->get();
                };
            } else {
                $closure = function( fvRoot $item ) use ( $field ){
                    return $item->getId();
                };
            }
        }

        return array_map( $closure, $values );
    }

}