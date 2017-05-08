<?php

class fvImageQuery{
    private $modifyList = array();

    private $extension;

    private $originImagePath;
    private $documentRoot;

    private $formats = array( "jpg", "png", "gif", "data-url" );

    function __construct( $imagePath, $documentRoot = "" ){
        $this->originImagePath = $imagePath;
        $this->documentRoot = $documentRoot;
    }

    /**
     * Resizes current image based on given width and/or height
     *
     * @param null $width
     * @param null $height
     * @param bool $ratio
     * @param bool $upsize
     * @return $this
     */
    function resize( $width = null, $height = null, $ratio = false, $upsize = true ){
        if( $width === null && $height === null ){
            return $this;
        }

        $this->modifyList["resize"] = array(
            "width" => $width,
            "height" => $height,
            "ratio" => $ratio,
            "upsize" => $upsize
        );

        return $this;
    }

    function resizeCanvas( $width, $height, $anchor = "center", $relative=false, $bgcolor = "rgba(0,0,0,.0)" ){
        $this->modifyList["resizeCanvas"] = array(
            "width" => $width,
            "height" => $height,
            "anchor" => $anchor,
            "relative" => $relative,
            "bgcolor" => $bgcolor
        );

        return $this;
    }

    /**
     * Combine cropping and resizing to format image in a smart way.
     * The method will find the best fitting aspect ratio of your given
     * width and height on the current image automatically, cut it out
     * and resize it to the given dimension.
     *
     * @param null $width
     * @param null $height
     * @return $this
     */
    function grab( $width = null, $height = null ){
        if( $width === null && $height === null ){
            return $this;
        }

        $this->modifyList["grab"] = array(
            "width" => $width,
            "height" => $height,
        );

        return $this;
    }

    function fit( $width, $height ){
        if( $width === null && $height === null ){
            return $this;
        }

        $this->modifyList["fit"] = array(
            "width" => $width,
            "height" => $height,
        );

        return $this;
    }

    /**
     * Cut out a rectangular part of the current image with given width and height.
     * Define optional x,y coordinates to move the top-left corner of the cutout to a certain position.
     *
     * @param $width int
     * @param $height int
     * @param $x int
     * @param $y int
     * @return fvImageQuery
     */
    function crop( $width, $height, $x = 0, $y = 0 ){
        $this->modifyList["crop"] = array(
            "width" => $width,
            "height" => $height,
            "pos_x" => $x,
            "pos_y" => $y
        );

        return $this;
    }

    /**
     * Turns image into a greyscale version.
     *
     * @return $this
     */
    function grayScale(){
        $this->modifyList["grayscale"] = array(true);

        return $this;
    }

    /**
     * Apply a gaussian blur filter with a certain amount on the current image.
     * Note: Performance intensive on larger amounts of blur. Use with care.
     *
     * @param int $blur The amount of the blur strength. Usually values between 1 and 10 will do the work in acceptable time.
     * @return $this
     */
    function blur( $blur ){
        $this->modifyList["blur"] = array( $blur );

        return $this;
    }

    /**
     * Mirror the current image horizontally or vertically by specifying the mode.
     * @param $vertical bool if false horisontal flip will be used
     * @return fvImageQuery
     */
    function flip( $vertical = true ){
        $this->modifyList["flip"] = array( "mode" => ( $vertical ) ? "v" : "h" );

        return $this;
    }

    /**
     * Set the opacity in percent of the current image ranging from 100% for opaque and 0% for full transparency.
     *
     * @param $transparency int the new percent of transparency for the current image.
     * @return $this
     */
    function opacity( $transparency = 100 ){
        $this->modifyList["opacity"] = array( "transparency" => $transparency );

        return $this;
    }

    /**
     * Reverses all colors of the current image
     * @return $this
     */
    function invert(){
        $this->modifyList["invert"] = array( true );

        return $this;
    }

    /**
     * Applies a pixelation effect to the current image with a given size of pixels.
     * The advanced mode of the GD Library is turned on by default and can be turned off by passing a boolean false.
     *
     * @param int $size Size of the pixels.
     * @param bool $advanced Whether to use advanced pixelation of GD Library or not
     * @return $this
     */
    function pixelate( $size = 1, $advanced = false ){
        $this->modifyList["pixelate"] = array(
            "size" => $size,
            "advanced" => $advanced
        );

        return $this;
    }

    /**
     * Rotate the current image by a given angle.
     * Optionally define a background color for the uncovered zone after the rotation.
     *
     * @param float $angle The rotation angle in degrees to rotate the image anticlockwise.
     * @param Strings $backgroundColor A background color for the uncovered zone after the rotation. The background color can be passed in in different color formats.
     *
     * @return $this
     */
    function rotate( $angle = .0, $backgroundColor = "rgba(255,255,255, .0)" ){
        $this->modifyList["rotate"] = array(
            "angle" => $angle,
            "bgcolor" => $backgroundColor
        );

        return $this;
    }

    /**
     * Encodes the current image in given format and given image quality.
     *
     * @param Strings $format
     * @param int $quality Define optionally the quality encoded image data ranging from 0 (poor quality, small file) to 100 (best quality, big file). The default value is 90.
     *
     * @return $this
     */
    function encode( $format = "png", $quality = 90 ){
        if( !in_array( $format, $this->formats ) ){
            return $this;
        }

        $this->modifyList["encode"] = array(
            "format" => $format,
            "quality" => $quality
        );
        $this->extension = $format;
        return $this;
    }

    function render( $relative = true ){
        $modifiedFilePath = $this->getModifiedFileName();

        if( !file_exists( $modifiedFilePath ) ){
            $imageProcessor = new Image($this->originImagePath);
            $this->applyModifies( $imageProcessor );
            $imageProcessor->save( $modifiedFilePath );
            chmod( $modifiedFilePath, 0664 );
        }

        if( $relative ){
            $relativePath = str_replace( $this->documentRoot, "", $modifiedFilePath );
            return str_replace( "//", "/", "/".$relativePath );
        }

        return $modifiedFilePath;
    }

    function __toString(){
        return $this->render();
    }

    function renderUri(){
        $url = $this->render();

        return "http://" . $_SERVER["HTTP_HOST"] . $url;
    }

    private function getModifiedFileName(){
        $modificator = md5( json_encode( $this->modifyList ) );
        $fileInfo = pathinfo( $this->originImagePath );

        $fileName = $fileInfo["filename"] . "_" . $modificator;
        $extension = ($this->extension) ? $this->extension : $fileInfo["extension"];

        return $fileInfo["dirname"] . "/" . $fileName . "." . $extension;
    }

    private function applyModifies( Intervention\Image\Image $handler ){
        foreach( $this->modifyList as $modifier => $parameters ){
            call_user_func_array(
                array( $handler, $modifier ),
                $parameters
            );
        }
    }
}