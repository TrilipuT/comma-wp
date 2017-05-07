<?php

/**
 * Created by cah4a.
 * Time: 01:25
 * Date: 22.10.15
 */
class Album extends fvRoot
{

    public function shareImage(){
        if( $this->shareImage->get() ){
            return $this->shareImage->__toString();
        }

        return $this->fullImage->__toString();
    }

    public function shareTitle()
    {
        if( $this->shareTitle->get() ){
            return $this->shareTitle->get();
        }

        return $this->artist->get();
    }

    public function shareDescription()
    {
        if( $this->shareDescription->get() ){
            return $this->shareDescription->get();
        }

        return $this->title->get();
    }

    public function color( $steps )
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $this->color->get());
        if( strlen($hex) == 3 ) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach( $color_parts as $color ) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

}