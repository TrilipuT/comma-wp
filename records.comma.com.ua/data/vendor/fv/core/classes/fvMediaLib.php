<?php

class fvMediaLib{
    const THUMB_WIDTH = 1;
    const THUMB_SQUADRE = 2;
    const THUMB_HEIGHT = 3;
    const THUMB_SQUARE = 4;
    const THUMB_OUTER = 5;
    const THUMB_EXACT = 6;

    /**
     * Method to create Image Thumbnail (use gd library) allowed Images - image/gif, image/jped and image/png
     *
     * @param Strings $srcFileName - sourse Image Name
     * @param Strings $destFileName - destination Image Name
     * @param array $params
     */
    public static function createThumbnail( $srcFileName, $destFileName, $params = array() ){

        $allowedTypes = array( 'IMAGETYPE_GIF', 'IMAGETYPE_JPEG', 'IMAGETYPE_PNG' );

        if( !empty( $params['type'] ) ){
            $default_type = $params['type'];
        }

        if( !empty( $params['width'] ) ){
            $width = $params['width'];
        }

        if( !empty( $params['height'] ) ){
            $height = $params['height'];
        }

        if( !empty( $params['resize_type'] ) ){
            $type = $params['resize_type'];
        }

        list( $orig_width, $orig_height, $orig_type ) = getimagesize( $srcFileName );

        $sourceOffsetX = ( !empty( $params['offsetX'] ) ) ? intval( $params['offsetX'] ) : 0;
        $sourceOffsetY = ( !empty( $params['offsetY'] ) ) ? intval( $params['offsetY'] ) : 0;

        switch( $type ){
            case self::THUMB_WIDTH:
                if( $orig_width > $width ){
                    $ratio = ( $width / $orig_width );
                    $height = round( $orig_height * $ratio );
                }
                else{
                    $width = $orig_width;
                    $height = $orig_height;
                }
                break;
            case self::THUMB_HEIGHT:
                if( $orig_height > $height ){
                    $ratio = ( $height / $orig_height );
                    $width = round( $orig_width * $ratio );
                }
                else{
                    $width = $orig_width;
                    $height = $orig_height;
                }
                break;
            case self::THUMB_SQUADRE:
                if( $width > $height ){
                    $val = "width";
                    $aval = "height";
                }
                else{
                    $val = "height";
                    $aval = "width";
                }

                if( ${'orig_' . $val} > ${$val} ){
                    $ratio = ( ${$val} / ${'orig_' . $val} );
                    ${$aval} = round( ${'orig_' . $aval} * $ratio );
                }
                else{
                    $width = $orig_width;
                    $height = $orig_height;
                }
                break;
            case self::THUMB_SQUARE:
                $value = ( $height > $width ) ? $width : $height;

                if( $orig_height > $orig_width ){
                    $ratio = ( $value / $orig_height );
                    $width = round( $orig_width * $ratio );
                }
                else{
                    $ratio = ( $value / $orig_width );
                    $height = round( $orig_height * $ratio );
                }
                break;
            case self::THUMB_OUTER:
                if( ( $orig_width / $orig_height ) > ( $width / $height ) ){
                    $orig_width_new = $width * ( $orig_height / $height );
                    $sourceOffsetX = round( ( $orig_width - $orig_width_new ) / 2 );
                    $orig_width = $orig_width_new;
                }
                else{
                    $orig_height_new = $height * ( $orig_width / $width );
                    $sourceOffsetY = round( ( $orig_height - $orig_height_new ) / 2 );
                    $orig_height = $orig_height_new;
                }
                break;
            case self::THUMB_EXACT:
                $orig_width = $width;
                $orig_height = $height;
                break;
            default:
                return false;
                break;
        }

        $origFileExt = '';

        if( $width == $orig_width && $height == $orig_height && !$sourceOffsetX && !$sourceOffsetY ){
            copy( $srcFileName, $destFileName );

            return true;
        }

        foreach( $allowedTypes as $allowedType ){
            if( defined( $allowedType ) && ( constant( $allowedType ) == $orig_type ) ){
                $origFileExt = strtolower( substr( $allowedType, strpos( $allowedType, "_" ) + 1 ) );
            }
        }

        if( !function_exists( $functionName = "imagecreatefrom" . $origFileExt ) ){
            return false;
        }

        if( ( $srcImage = call_user_func( $functionName, $srcFileName ) ) === false ){
            return false;
        }

        imageAlphaBlending( $srcImage, true );
        imagesavealpha( $srcImage, true );

        if( ( $dstImage = imagecreatetruecolor( $width, $height ) ) === false ){
            return false;
        }

        imageAlphaBlending( $dstImage, true );
        imagesavealpha( $dstImage, true );

        $transparent = imagecolorallocatealpha( $dstImage, 255, 255, 255, 127 );
        imagefilledrectangle( $dstImage, 0, 0, $width, $height, $transparent );

        imagecopyresampled( $dstImage, $srcImage, 0, 0, $sourceOffsetX, $sourceOffsetY, $width, $height, $orig_width,
                            $orig_height );

        if( !function_exists( $functionName = "image" . $origFileExt ) ){
            return false;
        }

//        header("Content-Type: " . image_type_to_mime_type($orig_type));
        if( call_user_func( $functionName, $dstImage, $destFileName ) === false ){
            return false;
        }

        imagedestroy( $srcImage );
        imagedestroy( $dstImage );

        return true;
    }

    static function setTransparency( $new_image, $image_source ){
        $transparencyIndex = imagecolortransparent( $image_source );
        $transparencyColor = array( 'red' => 255, 'green' => 255, 'blue' => 255 );

        if( $transparencyIndex >= 0 ){
            $transparencyColor = imagecolorsforindex( $image_source, $transparencyIndex );
        }

        $transparencyIndex = imagecolorallocate( $new_image, $transparencyColor['red'], $transparencyColor['green'],
                                                 $transparencyColor['blue'] );
        imagefill( $new_image, 0, 0, $transparencyIndex );
        imagecolortransparent( $new_image, $transparencyIndex );
    }

    public static function calcDementions( $srcFileName, $params = array() ){
        if( !empty( $params['type'] ) ){
            $default_type = $params['type'];
        }
        else{
            $default_type = fvSite::$fvConfig->get( 'images.default_type', 'normal' );
        }
        if( !empty( $params['width'] ) ){
            $width = $params['width'];
        }
        else{
            $width = (int)fvSite::$fvConfig->get( "images.{$default_type}.width" );
        }
        if( !empty( $params['height'] ) ){
            $height = $params['height'];
        }
        else{
            $height = (int)fvSite::$fvConfig->get( "images.{$default_type}.height" );
        }
        if( !empty( $params['resize_type'] ) ){
            $type = $params['resize_type'];
        }
        else{
            $type = (int)fvSite::$fvConfig->get( "images.{$default_type}.type" );
        }

        list( $orig_width, $orig_height, $orig_type ) = getimagesize( $srcFileName );
        switch( $type ){
            case self::THUMB_WIDTH:
                if( $orig_width > $width ){
                    $ratio = ( $width / $orig_width );
                    $height = (int)round( $orig_height * $ratio );
                }
                else{
                    $width = $orig_width;
                    $height = $orig_height;
                }
                break;
            case self::THUMB_SQUADRE:

                if( $width > $height ){
                    $val = "width";
                    $aval = "height";
                }
                else{
                    $val = "height";
                    $aval = "width";
                }

                if( ${'orig_' . $val} > ${$val} ){
                    $ratio = ( ${$val} / ${'orig_' . $val} );
                    ${$aval} = (int)round( ${'orig_' . $aval} * $ratio );
                }
                else{
                    $width = $orig_width;
                    $height = $orig_height;
                }
                break;
            default:
                return false;
                break;
        }

        return array( $width, $height, $orig_type, "width=\"$width\" height=\"$height\"" );
    }

    /**
     * Returns path to temporal file
     *
     * @param mixed $fileName
     * @param mixed $real is path absolute or real
     *
     * @returns Strings
     */
    public static function getTemporalFile( $fileName, $real = true ){
        $pathToDirectory = ( $real ) ? fvSite::$fvConfig->get( "path.upload.temp_image" )
            : fvSite::$fvConfig->get( "path.upload.web_temp_image" );

        return $pathToDirectory . $fileName;
    }

    public static function addStrip( $image, $ribboned ){
        // получаем имя изображения, используемого в качестве водяного знака
        $image_path = FV_ROOT . "../theme/images/ribbon.png";
        // получаем размеры исходного изображения
        list( $owidth, $oheight ) = getimagesize( $image );
        // задаем размеры для выходного изображения
        $width = $owidth;
        $height = $oheight;
        // создаем выходное изображение размерами, указанными выше
        $im = imagecreatetruecolor( $width, $height );
        $img_src = imagecreatefromjpeg( $image );
        // наложение на выходное изображение, исходного
        imagecopyresampled( $im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight );
        $watermark = imagecreatefrompng( $image_path );
        // получаем размеры водяного знака
        list( $w_width, $w_height ) = getimagesize( $image_path );
        // определяем позицию расположения водяного знака
        $pos_x = $width - $w_width;
        $pos_y = 0;
        // накладываем водяной знак
        imagecopy( $im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height );
        // сохраняем выходное изображение, уже с водяным знаком в формате jpg и качеством 100
        imagejpeg( $im, $ribboned, 100 );
        // уничтожаем изображения
        imagedestroy( $im );
        unlink( $image );

        return true;
    }

    function doResize( $inputfile, $outputfile, $new_width, $new_height ){
        $foto = $inputfile;
        $destfile = $outputfile;
        if( !is_file( $foto ) ) //file does not exist
        {
            return false;
        }

        $size = getimagesize( "$foto" );
        if( !$size ) //getimagesize fаiled
        {
            return false;
        }

        $width = $size[0];
        $height = $size[1];
        //            echo "[$width $height]<br>";

        if( ( $width > $new_width ) || ( $height > $new_height ) ){
            if( $width / $height > $new_width / $new_height ){
                $nw = $new_width;
                $nh = $height / $width * $new_width;
                $dst_x = 0;
                $dst_y = ( $new_height - $nh ) / 2;
                $dst_w = $new_width;
                $dst_h = $nh;
            }
            else{
                $nh = $new_height;
                $nw = $width / $height * $new_height;
                $dst_y = 0;
                $dst_x = ( $new_width - $nw ) / 2;
                $dst_h = $new_height;
                $dst_w = $nw;
            }

            $srcImage = ImageCreateFromJPEG( $foto );

            $destWidth = $new_width;
            $destHeight = $new_height;
            $destImage = imagecreatetruecolor( $destWidth, $destHeight );
            $white = imagecolorallocate( $destImage, 255, 255, 255 );
            imagefill( $destImage, 0, 0, $white );
            imagecopyresampled( $destImage, $srcImage, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $width, $height );

            //            echo " $dst_x $dst_y $dst_w $dst_h <br>";
            ImageJPEG( $destImage, $destfile );
        }
        else{
            $dst_x = ( $new_width - $width ) / 2;
            $dst_y = ( $new_height - $height ) / 2;


            $srcImage = ImageCreateFromJPEG( $foto );

            $destWidth = $new_width;
            $destHeight = $new_height;
            $destImage = imagecreatetruecolor( $destWidth, $destHeight );
            $white = imagecolorallocate( $destImage, 255, 255, 255 );
            imagefill( $destImage, 0, 0, $white );
            imagecopyresampled( $destImage, $srcImage, $dst_x, $dst_y, 0, 0, $width, $height, $width, $height );

            //            echo " $dst_x $dst_y $dst_w $dst_h <br>";
            ImageJPEG( $destImage, $destfile );
        }

        return true;
    }

    static function setBackground( $source, $backgroundSource ){
        $sourceInfo = pathinfo( $source ) ;
        $fileName = $sourceInfo["filename"] . "-facebook." . $sourceInfo["extension"];
        $outputPath = $sourceInfo["dirname"] . "/" . $fileName;

        $output = imagecreatetruecolor( 400, 400 );
        $background = imagecreatefrompng( $backgroundSource );

        imagecopyresampled( $output, $background, 0, 0, 0, 0, 400, 400, 400, 400 );
        $pic = imagecreatefrompng( $source );

        list( $width, $height ) = getimagesize( $source );

        imagecopyresampled( $output, $pic, 0, 55, 0, 0, 400, 400, $width, $width );
        imagepng( $output, $outputPath );
        imagedestroy( $output );

        return $fileName;
    }
}
