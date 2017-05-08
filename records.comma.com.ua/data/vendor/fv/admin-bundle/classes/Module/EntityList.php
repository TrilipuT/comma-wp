<?php

namespace AdminBundle\Module;
use Field_String_File;
use Field_String_Password;

/**
 * Created by cah4a.
 * Time: 12:27
 * Date: 29.10.13
 */
abstract class EntityList extends \fvComponent
{

    private $offset = 0;
    private $perPage = 100;
    private $search;
    private $query;

    protected $defaultListViewClass = "Module_ItemView";

    public function getComponentName()
    {
        return "module";
    }

    /**
     * @return ItemView
     */
    public function getListView()
    {
        $class = $this->base->option( "listView.class", $this->defaultListViewClass );

        /** @var ItemView $listView */
        $listView = new $class();

        $options = $this->base->option( "listView.options", array() );
        $listView->setOptions( $options );

        return $listView;
    }

    public function __construct( Base $base )
    {
        $this->base = $base;
        $this->view()->entity = $base->getEntityName();
        $this->query = $this->base->getRootManager()->select();

        if( \fvSite::session()->getAdmin()->hasAcl($this->base->getName() . ".create") ){
            $this->view()->create = $this->base->option( 'create', true );
        }

        if( $this->base->option('where') ){
            $this->query->andWhere( $this->base->option('where') );
        }

        if( $this->base->option('order') ){
            $this->query->orderBy( $this->base->option('order') );
        }
        else{
            $className = $base->getEntityName();
            /** @var fvRoot $entity */
            $entity = new $className;

            if( $entity->isImplements( 'iActive' )  ){
                $this->query->andOrderBy( 'isActive', false );
            }

            if( $entity->isImplements( 'iWeighted' )  ){
                $this->query->andOrderBy( 'weight' );
            }
        }


    }

    public function using( \Closure $callable )
    {
        $this->query->using( $callable );
        return $this;
    }

    public function getEntities()
    {
        return $this->query->limit( $this->perPage, $this->offset )->getIterator();
    }

    public function getBase()
    {
        return $this->base;
    }

    public function offset( $offset )
    {
        $this->offset = $offset;
    }

    public function perPage( $perPage )
    {
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return Strings
     */
    public function getSearch()
    {
        return $this->search;
    }

    public function search( $string )
    {
        $this->search = $string;

        $searchFields = array();
        foreach( $this->base->getRootManager()->getEntity()->getFields( 'Field_String' ) as $key => $field ){
            if( $field instanceof Field_String_File || $field instanceof Field_String_Password ){
                continue;
            }

            $searchFields[] = "{$key} LIKE :search";
        }

        $term = "%{$string}%";
        $this->query->andWhere( implode( " OR ", $searchFields ), array( "search" => $term ) );

        return $this;
    }

    public function sort( array $sort )
    {
        foreach( $sort as $key => $order ){
            $this->query->andOrderBy( $key, $order < 1 );
        }

        return $this;
    }

    public function hasMore()
    {
        return $this->query->limit( null )->getCount() > $this->offset + $this->perPage;
    }

    public function query(){
        return $this->query;
    }
} 