<?php


namespace AdminBundle\Form;

use Form_Field;
use Form_Field_Group;
use Form_Root;
use fvField;
use fvRoot;
use Language;

/**
 * Created by cah4a.
 * Time: 17:46
 * Date: 11.03.14
 */
class LocaledRoot extends Form_Root
{

    private $languages = array();

    function __construct( fvRoot $entity, array $params = array() )
    {
        $this->view()->languages = $this->languages = Language::getManager()->getAll();
        parent::__construct( $entity, $params );
    }

    public function assignVars()
    {
        $fields = array();
        $groups = array();

        foreach( $this->getFields() as $field ){
            if( $field instanceof Form_Field_Group ){
                $groups[] = $field;
            }
            else {
                $fields[] = $field;
            }
        }

        $this->view()->assignParams( array(
            "fields" => $fields,
            "groups" => $groups,
        ) );
    }


    /**
     * @param fvField[] $fields
     */
    protected function createFields( $fields )
    {
        $factory = $this->getFormFieldFactory();

        foreach( $fields as $key => $field ){
            if( $field->isLanguaged() ){
                continue;
            }

            $formField = $factory->createFromFvField( $field );

            if( ! $formField instanceof Form_Field ){
                continue;
            }

            $this->addField( $key, $formField );
        }

        foreach( $this->languages as $lang ){
            $this->getEntity()->setLanguage( $lang );
            $group = new Form_Field_Group();

            foreach( $fields as $key => $field ){
                if( ! $field->isLanguaged() ){
                    continue;
                }

                $formField = $factory->createFromFvField( $field );

                if( ! $formField instanceof Form_Field ){
                    continue;
                }

                $group->addField( $key, $formField->setValue( $field->get() ) );
            }

            $this->addField( $lang->code->get(), $group );
        }
    }

    protected function process()
    {
        $values = $this->getValues();
        $entity = $this->getEntity();
        foreach( $this->languages as $lang ){
            $entity->setLanguage( $lang );
            $entity->hydrate( $values[$lang->code->get()] );
            unset($values[$lang->code->get()]);
        }

        $entity->hydrate( $values );

        return $entity->save();
    }


} 