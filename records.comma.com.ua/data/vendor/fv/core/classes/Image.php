<?php

/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 01.08.13
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */
class Image extends Intervention\Image\Image
{

    public function grab( $width = null, $height = null, $anchor = "center" )
    {
        if( is_array( $width ) ){
            $dimensions = $width;
            return $this->legacyGrab( $dimensions );
        }

        $width = is_numeric( $width ) ? intval( $width ) : null;
        $height = is_numeric( $height ) ? intval( $height ) : null;

        if( !is_null( $width ) OR !is_null( $height ) ){
            // if width or height are not set, define values automatically
            $width = is_null( $width ) ? $height : $width;
            $height = is_null( $height ) ? $width : $height;
        }
        else {
            // width or height not defined (resume with original values)
            throw new Exception( 'width or height needs to be defined' );
        }

        // ausschnitt berechnen
        $grab_width = $this->width;
        $ratio = $grab_width / $width;

        if( $height * $ratio <= $this->height ){
            $grab_height = round( $height * $ratio );
        }
        else {
            $grab_height = $this->height;
            $ratio = $grab_height / $height;
            $grab_width = round( $width * $ratio );
        }

        switch( $anchor ){
            case 'top-left':
            case 'left-top':
                $src_x = 0;
                $src_y = 0;
                break;

            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $src_x = ($grab_width < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = 0;
                break;

            case 'top-right':
            case 'right-top':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = 0;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $src_x = 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;

            case 'bottom-left':
            case 'left-bottom':
                $src_x = 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $src_x = ($grab_height < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            default:
            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $src_x = ($grab_width < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;
        }

        return $this->modifyPaste( 0, 0, $src_x, $src_y, $width, $height, $grab_width, $grab_height );
    }

    protected function modifyPaste( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h )
    {
        // create new image
        $image = imagecreatetruecolor( $dst_w, $dst_h );

        // preserve transparency
        imagealphablending( $image, false );
        imagesavealpha( $image, true );

        // copy content from resource
        imagecopyresampled( $image, $this->resource, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

        // set new content as recource
        $this->resource = $image;

        // set new dimensions
        $this->width = $dst_w;
        $this->height = $dst_h;

        return $this;
    }

    public function fit( $width, $height, $colorAnchor = "left-top" ){
        $wa = $width / $this->width;
        $ha = $height / $this->height;

        if( $wa > $ha ){
            $newWidth = $this->width * $ha;
            $newHeight = $height;
            $x = ceil( ( $width - $newWidth ) / 2 );
            $y = 0;
        } else {
            $newWidth = $width;
            $newHeight = $this->height * $wa;
            $x = 0;
            $y = ceil( ( $height - $newHeight ) / 2 );
        }

        $color = $this->parseColor( array_values($this->pickColorFromAnchor( $colorAnchor )) );

        // create new image
        $image = imagecreatetruecolor( $width, $height );

        imagefilledrectangle( $image, 0, 0, $width, $height, $color );

        // preserve transparency
        imagealphablending( $image, false );
        imagesavealpha( $image, true );

        // copy content from resource
        imagecopyresampled( $image, $this->resource, $x, $y, 0, 0, $newWidth, $newHeight, $this->width, $this->height );

        // set new content as resource
        $this->resource = $image;

        // set new dimensions
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function pickColorFromAnchor( $anchor ){
        switch( $anchor ){
            case 'top-left':
            case 'left-top':
                $src_x = 0;
                $src_y = 0;
                break;

            case 'top':
            case 'top-center':
            case 'top-middle':
            case 'center-top':
            case 'middle-top':
                $src_x = ($grab_width < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = 0;
                break;

            case 'top-right':
            case 'right-top':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = 0;
                break;

            case 'left':
            case 'left-center':
            case 'left-middle':
            case 'center-left':
            case 'middle-left':
                $src_x = 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;

            case 'right':
            case 'right-center':
            case 'right-middle':
            case 'center-right':
            case 'middle-right':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;

            case 'bottom-left':
            case 'left-bottom':
                $src_x = 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            case 'bottom':
            case 'bottom-center':
            case 'bottom-middle':
            case 'center-bottom':
            case 'middle-bottom':
                $src_x = ($grab_height < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $src_x = ($grab_width < $this->width) ? intval( $this->width - $grab_width ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( $this->height - $grab_height ) : 0;
                break;

            default:
            case 'center':
            case 'middle':
            case 'center-center':
            case 'middle-middle':
                $src_x = ($grab_width < $this->width) ? intval( ($this->width - $grab_width) / 2 ) : 0;
                $src_y = ($grab_height < $this->height) ? intval( ($this->height - $grab_height) / 2 ) : 0;
                break;
        }

        return $this->pickColor( $src_x, $src_y );
    }
}