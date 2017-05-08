<?php

class Form_FieldValidator_Uniq extends Form_FieldValidator
{

    /** @var fvField */
    private $field;

    /** @var fvRoot */
    private $entity;

    function __construct( fvField $field, fvRoot $entity = null )
    {
        $this->field = $field;
        $this->entity = $entity;
    }

    public function validate( Form_Field $field )
    {
        $value = $field->getValue();
        $entity = $this->entity;

        if( !$entity instanceof fvRoot ){
            $form = $field->getForm();

            if( !$form instanceof Form_Root ){
                throw new Exception( 'Form must be instance of form root' );
            }
            $entity = $form->getEntity();
        }

        $count = $entity->select()
            ->where( [
                $this->field->getKey() => $value
            ] )
            ->andWhereNotIn( "root." . $entity->getPkName(), $entity->getId() )
            ->getCount();

        return $count == 0;
    }

}