<?php
/**
 * Created by cah4a.
 * Time: 18:00
 * Date: 14.02.14
 */

class Updater_Parser {

    /** @var Updater_Row[] */
    private $updates = array();

    /** @var array */
    private $errors = array();

    function __construct( $data ){
        $this->verifyHeaders( array_keys(reset($data)) );

        foreach( $data as $key => $row ){
            if( $this->isEmpty($row) ){
                continue;
            }

            $update = new Updater_Row( $row );

            if( $update->hasErrors() ){
                $this->errors[$key] = $update->getErrors();
            } elseif( $update->isChanged() ){
                $this->updates[] = $update;
            }
        }
    }

    private function verifyHeaders( array $headers ){
        $required = array( "article" );
        foreach( $required as $key ){
            if( !in_array( $key, $headers ) ){
                throw new Exception("Нет обязательного столбца {$key}");
            }
        }

        $tail = array_diff( $headers, $required );

        $expected = array(
            "designer",
            "category",
            "material",
            "url",
            "name",
            "description",
            "price",
            "exclusive",
            "newduedate",
            "discount",
            "discountstartdate",
            "discountenddate",
            "isactive",
        );

        $tail = array_diff( $tail, $expected );

        if( !empty($tail) ){
            throw new Exception("Неизвестные столбцы: " . implode(", ", $tail));
        }
    }

    private function isEmpty( array $row ){
        return ! array_reduce($row, function( $oldValue, $element ){
            return ! empty($element) || $oldValue;
        }, false );
    }

    public function getChanges(){
        return $this->updates;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getChangedHeaders(){
        $cols = array();

        foreach( $this->updates as $row ){
            $cols = array_unique( array_merge( $cols, array_keys($row->getChanges()) ) );
        }

        $titles = array(
            "price" => "Цена",
            "categoryId" => "Категория",
            "designerId" => "Дизайнер",
            "materialId" => "Материал",
            "name" => "Название",
            "newDueDate" => "Новый до",
            "description" => "Описание",
            "exclusive" => "Эксклюзивный",
            "url" => "ЧПУ",
            "discountStartDate" => "Скидка с",
            "discountEndDate" => "Скидка до",
            "discount" => "Скидка",
            "isActive" => "Активен",
        );

        return array_intersect_key( $titles, array_flip($cols) );
    }


}