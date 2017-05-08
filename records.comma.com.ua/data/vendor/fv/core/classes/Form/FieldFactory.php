<?php

class Form_FieldFactory {

    public function createFromFvField( fvField $field ){
        $formField = null;

        if( $this->IsNotFormField( $field ) )
            return null;

        $formField = $this->build( $field );

        if( $formField instanceof Form_Field ){
            $this->castValue( $field, $formField );
            $this->castValidators( $field, $formField );
        }

        return $formField;
    }

    protected function IsNotFormField( fvField $field ){
        return
            $field instanceof Field_Datetime_Ctime ||
            $field instanceof Field_Datetime_Mtime ||
            $field instanceof Field_Foreign_Creator ||
            $field instanceof Field_Array ||
            $field instanceof Field_Foreign_Modifier;
    }

    /**
     * @param fvField $field
     * @return null|Form_Field
     */
    private function build( fvField $field ){
        $types = array( "Select", "Checkbox", "Datetime", "File", "String", "Relations" );

        foreach( $types as $type ){
            $method = "build" . $type;
            if( $formField = $this->$method( $field ) )
                return $formField;
        }

        return null;
    }

    protected function buildDatetime( fvField $field ){
        if( $field instanceof Field_Date ){
            return new Form_Field_Date();
        }

        if( $field instanceof Field_Datetime ){
            return new Form_Field_DateTime();
        }

        return null;
    }

    protected function buildCheckbox( fvField $field ){
        if( $field instanceof Field_Bool ){
            if( $field->isNullable() ){
                return new Form_Field_BoolSelect();
            }

            return new Form_Field_Checkbox();
        }
        return null;
    }

    protected function buildFile( fvField $field ){
        if( $field instanceof Field_String_Image )
            return Form_Field_Image::make()->setPath( $field->getPath( true ) );

        if( $field instanceof Field_String_File )
            return Form_Field_File::make()->setPath( $field->getPath( true ) );

        return null;
    }

    protected function buildString( fvField $field ){
        if( $field instanceof Field_String_Password ) return new Form_Field_String("password");

        if( $field instanceof Field_String_Email ){
            return new Form_Field_String("email", array( new Form_FieldValidator_Email() ) );
        }

        if( $field instanceof Field_Rich ){
            return new Form_Field_Rich();
        }

        if( $field instanceof Field_Text ){
            return new Form_Field_Textarea();
        }

        if( $field instanceof Field_String ){
            return new Form_Field_String("text");
        }

        if( $field instanceof Field_Int ){
            return new Form_Field_String("text");
        }

        if( $field instanceof Field_Float ){
            return new Form_Field_String("text");
        }

        return null;
    }

    protected function buildSelect( fvField $field ){
        if( $field instanceof Field_Sex ){
            return new Form_Field_SexSelect();
        }

        if( $field instanceof Field_String_List ){
            return Form_Field_Select::make( $field->getList() )->setNullable( $field->isNullable() );
        }

        if( $field instanceof Field_Foreign ){
            $manager = fvManagersPool::get( $field->getEntityName() );
            return Form_Field_Tags::make($manager)->setNullable( $field->isNullable() );
        }

        return null;
    }

    protected function buildRelations( fvField $field ){
        if( $field instanceof Field_References ){
            $manager = fvManagersPool::get( $field->getForeignEntity() );
            return Form_Field_Tags::make($manager)->setMultiple(true)->setNullable( $field->isNullable() );
        }

        return null;
    }

    /**
     * @param fvField $field
     * @param Form_Field $formField
     */
    public function castValidators( fvField $field, Form_Field $formField ){
        if( ! $field->isNullable() && ! $formField instanceof Form_Field_Checkbox ){
            $formField->addValidator( "required" );
        }

        if( $field instanceof Field_String ){
            if( ! is_null($field->getLength()) ){
                $formField->addValidator( new Form_FieldValidator_MaxLength($field->getLength()) );
            }
        }

        if( $field instanceof Field_String_Url ){
            $formField->addValidator( new Form_FieldValidator_Url() );
        }

        if( $field instanceof Field_Price ){
            $formField->addValidator( new Form_FieldValidator_Price( $field ) );
            $formField->addValidator( new Form_FieldValidator_Min( 0 ) );
        } elseif ( $field instanceof Field_Int ){
            $formField->addValidator( new Form_FieldValidator_Integer() );
        }

        if ( $field instanceof Field_Float ){
            $formField->addValidator( new Form_FieldValidator_Float() );
        }

        if( $field instanceof Field_Percent ){
            $formField->addValidator( new Form_FieldValidator_Min( 0, true ) );
            $formField->addValidator( new Form_FieldValidator_Max( 90, true ) );
        }

        if( $field->isUnique() ){
            $formField->addValidator( new Form_FieldValidator_Uniq( $field ) );
        }

        if( $field instanceof Field_String_Phone ){
            $formField->addValidator( new Form_FieldValidator_Phone() );
        }
    }

    /**
     * @param fvField $field
     * @param Form_Field $formField
     */
    public function castValue( fvField $field, Form_Field $formField ){
        if( $field instanceof Field_Price ){
            $formField->setValue( $field->asFloat() );
            return;
        }

        if( $field instanceof Field_References ){
            $formField->setValue( array_keys($field->get()) );
            return;
        }

        if( ! $field instanceof Field_String_Password )
            $formField->setValue( $field->get() );
    }

}