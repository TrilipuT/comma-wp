<?php

class Field_String_Url extends Field_String {

    public function generate( $string, fvRoot $entity = null ){
        $translit = new Translit;
        $translitUrl = $url = $translit->transliterate( $string );

        if( $entity instanceof fvRoute ){
            $count = 0;
            while( $entity->find( [ $this->getKey() => $url ] ) ){
                $url = $translitUrl . "-" . ( ++$count );
            }
        }

        $this->set($url);
    }

}