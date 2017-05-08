<?php

class Form_Root extends fvForm {

    private $entity;

    function __construct( fvRoot $entity, array $params = array() ){
        $this->entity = $entity;

        $fields = $entity->getFields();

        if( !empty( $params['fields'] ) ){
            $fields = array_intersect_key( $fields, array_flip($params['fields']) );
            $fields = array_replace( array_flip($params['fields']), $fields );
        }

        if( !empty( $params['exclude'] ) )
            $fields = array_diff_key( $fields, array_flip($params['exclude']) );

        $constrains = $entity->getFields('Field_Constraint');

        if( isset( $params["constraints"] ) ){
            $constrains = array_diff_key( $constrains, array_flip( $params["constraints"] ) );
        }

        if( empty( $params['fields'] ) && empty( $params['exclude'] ) ){
            $fields = array_diff_key( $fields, $constrains );
        }

        $this->createFields( $fields );

        if( !empty($params['readonly']) ){
            foreach( $params['readonly'] as $field ){
                $this->getField($field)->disabled();
            }
        }

        $this->setContainer( $entity->getEntity() );
    }

    function getName(){
        return $this->getEntity()->getEntity() . "_Form";
    }

    protected function process(){
        $passwordFields = $this->getEntity()->getFields('Field_String_Password');

        foreach( $this->toArray() as $key => $value ){
            if( isset($passwordFields[$key]) ){
                if( empty($value) )
                    continue;
            }

            $this->getEntity()->$key = $value;
        }
        return $this->getEntity()->save();
    }

    final public function getEntity(){
        return $this->entity;
    }

    final protected function setEntity( fvRoot $entity ){
        $this->entity = $entity;
        return $this;
    }

    protected function getFormFieldFactory(){
        return new Form_FieldFactory();
    }

    protected function createFields( $fields ){
        $factory = $this->getFormFieldFactory();
        foreach( $fields as $key => $field ){
            $formField = $factory->createFromFvField( $field );

            if( ! $formField instanceof Form_Field )
                continue;

            $this->addField($key, $formField);
        }
    }


}