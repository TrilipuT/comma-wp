<?php


abstract class fvLayout extends Component_Extended {

    private $body;
    private $title;

    final function getComponentName() {
        return 'layout';
    }

    final public function setTitle( $title ){
        $this->title = strip_tags($title);

        return $this;
    }

    final public function getTitle(){
        return $this->title;
    }

    final public function setBody( $body ){
        $this->body = $body;
        $this->view()->body = $body;
        return $this;
    }

    final public function getBody(){
        return $this->body;
    }

    final public function fillMeta( fvRoot $entity ){
        if( ! $entity->isImplements("iMeta") ){
            throw new Exception( "Can't fill layout meta from entity " . get_class($entity) . " cuz, it's doesn't implement iMeta");
        }

        if( $entity->metaTitle->get() )
            $this->setTitle( $entity->metaTitle->get() );

        if( $entity->metaDescription->get() )
            $this->addMeta( "description", $entity->metaDescription->get() );

        if( $entity->metaKeywords->get() )
            $this->mergeKeywords($entity->metaKeywords->get() );

        return $this;
    }

}