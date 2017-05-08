<?php
/**
 * Created by cah4a.
 * Time: 18:01
 * Date: 14.02.14
 */

class Updater_Repository {

    /**
     * @return Designer[]
     */
    public static function designers(){
        static $designers;

        if( empty($designers) ){
            $designers = Designer::select()->aggregateBy("name", true)->fetchAll();
        }

        return $designers;
    }

    /**
     * @return Material[]
     */
    public static function materials(){
        static $materials;

        if( empty($materials) ){
            $materials = Material::select()->aggregateBy("name", true)->fetchAll();
        }

        return $materials;
    }

    /**
     * @return Category[]
     */
    public static function categories(){
        static $categories;

        if( empty($categories) ){
            $categories = Category::select()->aggregateBy("name", true)->fetchAll();
        }

        return $categories;
    }

    /**
     * @param $name
     * @return Designer
     * @throws Exception
     */
    public static function getDesigner( $name ){
        $designers = self::designers();

        if( !isset( $designers[$name] ) )
            throw new Exception("Неизвестный дизайнер {$name}");

        return $designers[$name];
    }

    /**
     * @param $name
     * @return Category
     * @throws Exception
     */
    public static function getCategory( $name ){
        $categories = self::categories();

        if( !isset( $categories[$name] ) )
            throw new Exception("Неизвестная категория {$name}");

        return $categories[$name];
    }

    /**
     * @param $name
     * @return Material
     * @throws Exception
     */
    public static function getMaterial( $name ){
        $materials = self::materials();

        if( !isset( $materials[$name] ) )
            throw new Exception("Неизвестный материал {$name}");

        return $materials[$name];
    }

    /**
     * @param $article
     * @return Product|null
     * @throws Exception
     */
    public static function getProduct( $article ){
        static $products;

        if( empty($products) ){
            $products = Product::select()->aggregateBy("article", true)->fetchAll();
        }

        return $products[$article];
    }

    /**
     * @param $key
     * @param $value
     * @return Category|Designer|Material|null|Product
     * @throws Exception
     */
    public static function get( $key, $value ){
        switch( $key ){
            case 'category': return self::getCategory($value);
            case 'designer': return self::getDesigner($value);
            case 'material': return self::getMaterial($value);
            case 'product': return self::getProduct($value);

            case 'categoryId':
                foreach( self::categories() as $entity ){
                    if( $entity->getId() == $value )
                        return $entity;
                }
                return false;

            case 'materialId':
                foreach( self::materials() as $entity ){
                    if( $entity->getId() == $value )
                        return $entity;
                }
                return false;

            case 'designerId':
                foreach( self::materials() as $entity ){
                    if( $entity->getId() == $value )
                        return $entity;
                }
                return false;

        }

        throw new Exception("Unknown relation {$key}");
    }

}