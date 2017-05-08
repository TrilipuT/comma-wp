<?php
/**
 * Created by cah4a.
 * Time: 18:01
 * Date: 14.02.14
 */

class Updater_Field {

    /** @var \Product */
    private $product;

    private $changes = array();

    private static $map = array(
        "isActive" => 'bool',
        "exclusive" => 'bool',
        "discount" => 'percent',
        "name" => 'text',
        "description" => 'text',
        "newDueDate" => 'date',
        "discountStartDate" => 'date',
        "discountEndDate" => 'date',
        "designer" => 'foreign',
        "material" => 'foreign',
        "category" => 'foreign',
    );

    function __construct( Product $product ){
        $this->product = $product;
    }

    public function update( $key, $value ){
        if( isset( self::$map[$key] ) ){
            $methodName = 'update' . ucfirst(self::$map[$key]);
        } else
            $methodName = 'update' . ucfirst($key);

        if( ! method_exists( $this, $methodName ) ){
            throw new Exception("Неизвестный столбец {$key}");
        }

        $this->{$methodName}( $key, $value );
    }

    public function getChanges(){
        return $this->changes;
    }

    protected function updateArticle( $key, $value ){
        if( empty($value) ){
            throw new Exception("Артикул не может быть пустым");
        }

        if( $this->product->isNew() ){
            $this->changes[$key] = $value;
        }
    }

    protected function updatePercent( $key, $value ){
        if( empty($value) || $value == "0" ){
            $value = null;
        } else {
            if( $value != (string)intval($value) ){
                throw new Exception("Неправильный формат скидки");
            }

            if( $value > 99 ){
                throw new Exception("Сумма скидки не может быть больше 99");
            }

            if( $value < 0 ){
                throw new Exception("Сумма скидки не может быть меньше 0");
            }
        }

        if( $this->getField($key)->get() != $value ){
            $this->changes[$key] = $value;
        }
    }

    protected function updateBool( $key, $value ){
        if( $this->getField($key)->get() != (bool)$value ){
            $this->changes[$key] = (bool)$value;
        }
    }

    protected function updateDate( $key, $value ){
        if( !empty($value) ){
            $value = strtotime("today", (intval($value) - 25569) * 86400);
        } else
            $value = null;

        if( $this->getField($key)->asTimestamp() != $value ){
            $this->changes[$key] = date('Y-m-d', $value);
        }
    }

    protected function updateUrl( $key, $value ){
        if( empty($value) ){
            throw new Exception("ЧПУ не может быть пустым");
        }

        if( $this->getField($key)->get() != $value ){
            if( Product::select()->where( array("url" => $value) )->getCount() > 0 ){
                throw new Exception("ЧПУ {$value} уже занято другим товаром");
            }

            $this->changes[$key] = $value;
        }
    }

    protected function updateText( $key, $value ){
        $oldVal = preg_replace("/\r/", "", $this->getField($key)->get());
        $value =  preg_replace("/\r/", "", $value);
        if( $oldVal != $value ){
            $this->changes[$key] = $value;
        }
    }

    protected function updatePrice( $key, $value ){
        if( empty($value) ){
            throw new Exception("Цена не может быть пустой");
        }

        if( $value != (string)floatval($value) ){
            throw new Exception("Неправильный формат цены");
        }

        if( $value < 0.01 ){
            throw new Exception("Цена должна быть больше нуля");
        }

        $value = floatval($value);
        if( abs($this->getField( $key )->asFloat() - floatval($value)) > 0.005 ){
            $this->changes[$key] = round($value * Field_Price::discretization());
        }
    }

    public function updateForeign( $key, $value ){
        $foreign = $this->product->getForeign($key);

        $fKey = $foreign->getKey();

        if( empty($value) && $foreign->get() ){
            $this->changes[$fKey] = null;
            return;
        }

        $relatedEntity = Updater_Repository::get( $key, $value );

        if( $foreign->get() != $relatedEntity->getId() ){
            $this->changes[$fKey] = $relatedEntity->getId();
        }
    }

    protected function getField( $key ){
        return $this->product->getField($key);
    }
}