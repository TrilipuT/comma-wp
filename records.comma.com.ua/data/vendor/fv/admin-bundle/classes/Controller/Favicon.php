<?php

namespace AdminBundle\Controller;

class Favicon extends \fvController
{

    /**
     * @route /favicon
     * @option security off
     */
    function indexAction()
    {
        $template = imagecreatefrompng(__DIR__ . "/../../resources/favicon.png");
        $size = imagesx($template);

        $letter = ucfirst( strtolower( $_SERVER['HTTP_HOST'][0] . $_SERVER['HTTP_HOST'][1] ) );

        $image = imagecreatetruecolor( $size, $size );
        imagealphablending( $image, false );
        imagesavealpha( $image, true );
        $transparency = imagecolorallocatealpha( $image, 0, 0, 0, 127 );
        imagefilledrectangle( $image, 0, 0, $size, $size, $transparency );

        $bgColor = $this->hslToRgb( crc32($_SERVER['HTTP_HOST']), 1, .4 );

        for( $x = 0; $x < $size; $x++ )
            for( $y = 0; $y < $size; $y++ ){
                $color = imagecolorat( $template, $x, $y );
                $color = imagecolorsforindex( $image, $color );
                //$n = $color['red'] * $bgColor[0] + $color['green'] * $bgColor[1] + $color['blue'] * $bgColor[2];
                $color['red'] = $this->overlay($color['red'], $bgColor[0], 1 -$color['alpha']/127);
                $color['green'] = $this->overlay($color['green'], $bgColor[1], 1- $color['alpha']/127);
                $color['blue'] = $this->overlay($color['blue'], $bgColor[2], 1 -$color['alpha']/127);
                imagesetpixel( $image, $x, $y, imagecolorallocatealpha( $image, $color['red'], $color['green'], $color['blue'], $color['alpha'] ) );
            }

        imagealphablending( $image, true );
        $color = imagecolorallocatealpha( $image, 255, 255, 255, 0 );
        $font = __DIR__ . "/../../resources/helioscond-bold-webfont.ttf";
        $textSize = imagettfbbox( 20, 0, $font, $letter );
        $textWidth = $textSize[2] - $textSize[0];
        $textHeight = $textSize[1] - $textSize[7];
        $black = imagecolorallocatealpha( $image, 0, 0, 0, 100 );
        $tx = $size / 2 - $textWidth / 2;
        $ty = $size / 2 + $textHeight / 2 - 1;
        imagettftext( $image, 20, 0, $tx - 1, $ty + 1, $black, $font, $letter );
        imagettftext( $image, 20, 0, $tx - 1, $ty - 1, $black, $font, $letter );
        imagettftext( $image, 20, 0, $tx + 1, $ty + 1, $black, $font, $letter );
        imagettftext( $image, 20, 0, $tx + 1, $ty - 1, $black, $font, $letter );
        imagettftext( $image, 20, 0, $tx, $ty, $color, $font, $letter );

        header( 'Content-Type: image/png' );
        imagepng( $image );
        die;
    }

    public function overlay( $c1, $c2, $alpha = 1 ){
        if( $c1 > 127.5 ){
            $vu = (255 - $c1 ) / 127.5;
            $mv = $c1 - (255 - $c1);
            $nc = $mv + $vu * $c2;
        } else {
            $vu = $c1 / 127.5;
            $nc = $c2 * $vu;
        }

        return $c1 + ($nc - $c1) * $alpha;
    }

    private function hslToRgb( $h, $s, $l, $opacity = 0 ){
        $h = abs(($h % 360)/360);

        $r = $l;
        $g = $l;
        $b = $l;
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);

        if ($v > 0){
            $m = $l + $l - $v;
            $sv = ($v - $m ) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;

            switch ($sextant)
            {
                case 0:
                    $r = $v;
                    $g = $mid1;
                    $b = $m;
                    break;
                case 1:
                    $r = $mid2;
                    $g = $v;
                    $b = $m;
                    break;
                case 2:
                    $r = $m;
                    $g = $v;
                    $b = $mid1;
                    break;
                case 3:
                    $r = $m;
                    $g = $mid2;
                    $b = $v;
                    break;
                case 4:
                    $r = $mid1;
                    $g = $m;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $m;
                    $b = $mid2;
                    break;
            }
        }

        return [
            $r * 255.0,
            $g * 255.0,
            $b * 255.0,
            $opacity
        ];
    }
}