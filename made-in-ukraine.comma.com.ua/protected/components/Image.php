<?php

if ( ! function_exists( 'exif_imagetype' ) ) {
    function exif_imagetype ( $filename ) {
        if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
            return $type;
        }
    return false;
    }
}

class Image {
	public $tmp = 'tmp';
	public $availableExtensions = array('jpg','jpeg','png');
	
	private $path;
	private $res;
	private $current;
	private $width;
	private $height;
	private $type;
	private $ext;
	private $size;
	
	private $selection = array();

	private $onlyPng = false;
	
	public function __construct($onlyPng = false){
		if($onlyPng){
			$this->onlyPng = true;
		}
	}

	public function init() {
		
	}
	public function __toString() {
		return 'Image component';
	}
	
	public function load($path) {
		if (!file_exists($path)) throw new CException('Картинка не найдена');
		$m = exif_imagetype($path);
		if ($m != 2 && $m != 3) throw new CException('Поддерживаемые форматы иконки: jpeg, png');
		
		$this->path = $path;
		$info = @getimagesize($this->path);
		list($this->width, $this->height) = $info;
		list($this->type, $this->ext) = @explode('/', $info['mime']);
		if ($this->ext == 'pjpeg') $this->ext = 'jpeg';
		$this->size = filesize($this->path);
		
		$create = 'imagecreatefrom'.$this->ext;
		$this->res = $create($this->path);
		$this->current = $this->res;
		imagesavealpha($this->res, true);
		
		$this->select();
		
		return $this;
	}
	public function loadFromURL($url) {
		
		if (empty($this->tmp) || !is_dir($this->tmp)) throw new CException('Временная директория не существует');
		if (substr(sprintf('%o', fileperms($_SERVER['DOCUMENT_ROOT'].'/'.$this->tmp)), -3) != '777') throw new CException('Временная директория не доступна для записи');
		
		$fp = fopen($url, 'rb');
		if (!$fp) throw new CException('Не могу открыть удаленный файл');
		
		$fileContent = '';
		while(!feof($fp)) {
			$fileContent .= fread($fp, 8192);
		}
		fclose($fp);
		
		$fileName = uniqid('imagetmp');
		$fp = fopen($this->tmp.'/'.$fileName, 'wb');
		fwrite($fp, $fileContent);
		fclose($fp);
		
		return $this->load($this->tmp.'/'.$fileName);
	}
	public function select($selection=null) {
		if (is_null($selection)) $selection = array(0,0,$this->width,$this->height);
		if (is_array($selection)) {
			$this->selection = array($selection[0],$selection[1],$selection[2],$selection[3]);
		}
		return $this;
	}
	public function crop($size=null) {
		$create = 'imagecreatefrom'.$this->ext;
		
		// Нужный размер иконки
		$width = $size[0] ? $size[0] : $this->selection[2];
		$height = $size[1] ? $size[1] : $this->selection[3];
		
		// Получаем масштаб
		$scale = 1;
		$widthScale = $this->selection[2] / $width; 
		$heightScale = $this->selection[3] / $height;
		if ($widthScale > $heightScale) {
			$scale = $heightScale;
		} else {
			$scale = $widthScale;
		}
		
		// Актуальный размер иконки (без артефактов)
		$actualWidth = round($this->selection[2] / $scale);
		$actualHeight = round($this->selection[3] / $scale);
		

		$actualSizedThumbnail = imagecreatetruecolor($actualWidth, $actualHeight);
		imagesavealpha($actualSizedThumbnail, true);
		$transparent = imagecolorallocatealpha($actualSizedThumbnail, 0, 0, 0 ,127);
		imagefill($actualSizedThumbnail, 0, 0, $transparent);
		
		imagecopyresampled($actualSizedThumbnail, $this->res, 0, 0, $this->selection[0], $this->selection[1], 
						   $actualWidth, $actualHeight, $this->selection[2], $this->selection[3]);
		
		// Смещение для центрирования иконки
		$offsetX = round(($actualWidth - $width) / 2);
		$offsetY = round(($actualHeight - $height) / 2);


		$this->current = imagecreatetruecolor($width, $height);
		imagesavealpha($this->current, true);
		$transparent = imagecolorallocatealpha($this->current, 0, 0, 0 ,127);
		imagefill($this->current, 0, 0, $transparent);
		$this->imagecopymerge_alpha($this->current, $actualSizedThumbnail, 0, 0, $offsetX, $offsetY, $width, $height, 100);
		return $this;
	}



	public function scale($size=null) {
		$create = 'imagecreatefrom'.$this->ext;
		
		if ($size[0] == 'w') {
			$width = $size[1] ? $size[1] : $this->selection[2];
			if ($this->width <= $width) {
				$this->current = $this->res;
				return $this;
			}
			$scale = $this->selection[2] / $width;
		} else if ($size[0] == 'h') {
			$height = $size[1] ? $size[1] : $this->selection[3];
			if ($this->height <= $height) {
				$this->current = $this->res;
				return $this;
			}
			$scale = $this->selection[3] / $height;
		} else {
			// Нужный размер иконки
			$width = $size[0] ? $size[0] : $this->selection[2];
			$height = $size[1] ? $size[1] : $this->selection[3];
			
			if ($this->width <= $width && $this->height <= $height) {
				$this->current = $this->res;
				return $this;
			}
			
			// Получаем масштаб
			$scale = 1;
			$widthScale = $this->selection[2] / $width;
			$heightScale = $this->selection[3] / $height;
			if ($widthScale < $heightScale) {
				$scale = $heightScale;
			} else {
				$scale = $widthScale;
			}
		}
		
		// Актуальный размер иконки (без артефактов)
		$actualWidth = round($this->selection[2] / $scale);
		$actualHeight = round($this->selection[3] / $scale);
		
		$this->current = imagecreatetruecolor($actualWidth, $actualHeight);
		imagesavealpha($this->current, true);
		$transparent = imagecolorallocatealpha($this->current, 0, 0, 0 ,127);
		imagefill($this->current, 0, 0, $transparent);
		
		imagecopyresampled($this->current, $this->res, 0, 0, $this->selection[0], $this->selection[1], 
						   $actualWidth, $actualHeight, $this->selection[2], $this->selection[3]);
		
		return $this;
	}
	public function save($destination=null) {
		if (is_null($destination)) $destination = $this->path;
		
		//------------------------------
		if($this->onlyPng){
			$this->ext = 'png';
		} 
		//------------------------------

		
		$save = 'image'.$this->ext;
		$save($this->current, $destination.'.'.$this->ext, $this->ext == 'png' ? 9 : 100);
		$this->current = $this->res;
		return $destination.'.'.$this->ext;
	}
	public function close() {
		imagedestroy($this->res);
	}
	public function getPath() {
		return $this->path;
	}
	public function getExt() {
		return $this->ext;
	}
	public function getWidth() {
		return $this->width;
	}
	public function getHeight() {
		return $this->height;
	}
	public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		if(!isset($pct)){ 
			return false;
		} 
		$pct /= 100; 
		// Get image width and height 
		$w = imagesx( $src_im ); 
		$h = imagesy( $src_im ); 
		// Turn alpha blending off 
		imagealphablending( $src_im, false ); 
		// Find the most opaque pixel in the image (the one with the smallest alpha value) 
		$minalpha = 127; 
		for( $x = 0; $x < $w; $x++ ) 
		for( $y = 0; $y < $h; $y++ ){ 
			$alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF; 
			if( $alpha < $minalpha ){ 
				$minalpha = $alpha; 
			} 
		} 
		//loop through image pixels and modify alpha for each 
		for( $x = 0; $x < $w; $x++ ){ 
			for( $y = 0; $y < $h; $y++ ){ 
				//get current alpha value (represents the TANSPARENCY!) 
				$colorxy = imagecolorat( $src_im, $x, $y ); 
				$alpha = ( $colorxy >> 24 ) & 0xFF; 
				//calculate new alpha 
				if( $minalpha !== 127 ){ 
					$alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha ); 
				} else { 
					$alpha += 127 * $pct; 
				} 
				//get the color index with new alpha 
				$alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha ); 
				//set pixel with the new color + opacity 
				if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){ 
					return false; 
				} 
			} 
		} 
		// The image copy 
		imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	}
 
	public static function image_mask(&$src, &$mask) {

	    imagesavealpha($src, true);
	    imagealphablending($src, false);
	    // scan image pixels
	    for ($x = 0; $x < imagesx($src); $x++) {
	        for ($y = 0; $y < imagesy($src); $y++) {

	            $mask_pix 		= imagecolorat($mask,$x,$y);
	            $mask_pix_color = imagecolorsforindex($mask, $mask_pix); 

	            if ($mask_pix_color['alpha'] < 127) {

	            	//var_dump($mask_pix_color['alpha']);

	                $src_pix 		= imagecolorat($src,$x,$y);
	                $src_pix_array 	= imagecolorsforindex($src, $src_pix);

                	$color 			= imagecolorallocatealpha($src, $src_pix_array['red'], $src_pix_array['green'], $src_pix_array['blue'], 127 - $mask_pix_color['alpha']);
	                imagesetpixel($src, $x, $y, $color );
	            }
	        }
	    } 

	    //exit;
	}

	public static function createMask($path, $id, $maskName){

        $file       = $_SERVER['DOCUMENT_ROOT'].$path.$id.'.png';  
        $marker_path= $_SERVER['DOCUMENT_ROOT'].'/img/mask/'.$maskName.'.png';  

        if($file){

            $imgInfo = getimagesize($file);         
            switch($imgInfo[2]) {
                case 2: //JPG
                    $img = imagecreatefromjpeg($file);
                    break;
                case 3: //PNG
                    $img = imagecreatefrompng($file); 
                    break; 
            }   
                 
            $mask = imagecreatefrompng($marker_path); 
            imagesavealpha($mask, true);
            //imagealphablending($mask, false);


            Yii::import('application.components.Image');
            Image::image_mask($img, $mask);  

            //$this->fileDelete($path.$id.'.png'); 

            imagepng($img, $_SERVER['DOCUMENT_ROOT'].$path.$id.'.png');                   
            return true;  

        } // end fileexist
    }

}