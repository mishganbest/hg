<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height, $save = false) {
		$width = round($width);
		$height = round($height);
		if (!file_exists(DIR_IMAGE . $filename) || !@is_file(DIR_IMAGE . $filename)) {
			return;
		}
		static $watermark;
		if (!$watermark) {
			$watermark = $this->config->get('watermark');
		}
		$info = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$basename = basename($filename);
		$new_image = 'cache/' . $width . '-' . $height . '/' . dirname($filename) . '/' . substr($basename, 0, strrpos($basename, '.')) . '.' . $extension;
		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}
			$image = new Image(DIR_IMAGE . $old_image);
			$image->resize($width, $height, $save);
			$image->save(DIR_IMAGE . $new_image);
			if (is_array($watermark) && $watermark['status']) {
				$dir = trim($info['dirname'], '/');
				if (in_array('all', $watermark['folders']) || in_array($dir, $watermark['folders'])) {
					$this->add_watermark(DIR_IMAGE . $new_image, $watermark);
				}
            }
		}
		$image_path = explode ('/', trim(DIR_IMAGE, '/'));
		$image_folder = end($image_path);
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . $image_folder . '/' . $new_image;
		} else {
			return $this->config->get('config_url') . $image_folder . '/' . $new_image;
		}
	}
	
	public function add_watermark($filename, $options) {
		if (isset($this->disabled) && $this->disabled) {
			return;
		}
		if (file_exists($filename)) {
			$file_info = getimagesize($filename);
			$info = array(
            	'width'  => $file_info[0],
            	'height' => $file_info[1],
            	'bits'   => $file_info['bits'],
            	'mime'   => $file_info['mime']
        	);
        	
			if ($info['mime'] == 'image/gif') {
				$image =  imagecreatefromgif($filename);
			} elseif ($info['mime'] == 'image/png') {
				$image = imagecreatefrompng($filename);
			} elseif ($info['mime'] == 'image/jpeg') {
				$image = imagecreatefromjpeg($filename);
			} else {
				return;
			}
    	} else {
			return false;
    	}
		if ((is_numeric($options['min']['width']) && $info['width'] < (float)trim($options['min']['width']))) {
			return;
		}
		if ((is_numeric($options['min']['height']) && (float)$info['height'] < (float)trim($options['min']['height']))) {
			return;
		}
		$scale = 1;
		if (is_numeric($options['scaling']['width']) && is_numeric($options['scaling']['height'])) {
			if ($info['width'] != $options['scaling']['width'] || $options['scaling']['height'] != $options['scaling']['height']) {
				$scale = max($info['width'] / $options['scaling']['width'], $info['height'] / $options['scaling']['height']);
			}
		} elseif (is_numeric($options['scaling']['width'])) {
			if ($info['width'] != $options['scaling']['width']) {
				$scale = $info['width'] / $options['scaling']['width'];
			}
		} elseif (is_numeric($options['scaling']['height'])) {
			if ($info['height'] != $options['scaling']['height']) {
				$scale = $info['height'] / $options['scaling']['height'];
			}
		}
		switch($options['type']) {
			case 'img':
				if (file_exists(DIR_IMAGE . $options['image']) && is_file(DIR_IMAGE . $options['image'])) {
					$watermark_image = DIR_IMAGE . $options['image'];
					
					if ($scale !== 1) {
						$watermark_info = getimagesize($watermark_image);
						$this->disabled = true;
						$watermark_image = $this->resize($options['image'], $watermark_info[0] * $scale, $watermark_info[1] * $scale);
						
						if ($watermark_image) {
							$image_path = explode ('/', trim(DIR_IMAGE, '/'));
							$image_folder = end($image_path);
							$watermark_image = str_replace($this->config->get('config_url') . $image_folder . '/', DIR_IMAGE, $watermark_image);
						} else {
							return;
						}
						$this->disabled = false;
					}
					
					$watermark_info = getimagesize($watermark_image);
					
					switch($watermark_info['mime']) {
						case 'image/gif':
							$watermark = imagecreatefromgif($watermark_image);
							break;
						case 'image/png':
							$watermark = imagecreatefrompng($watermark_image);
							break;
						case 'image/jpeg':
							$watermark = imagecreatefromjpeg($watermark_image);
							break;
						default:
							return;
					}
					
					$watermark_width  = imagesx($watermark);
					$watermark_height = imagesy($watermark);
				} else {
					return;
				}
				break;
			case 'text':
				$angle = 0;
				$text = trim($options['watermark_text']);
				if (empty($text) || $text == '') {
					return;
				}
				$font = DIR_SYSTEM . 'fonts/' . $options['watermark_font'];
				$fontsize = (float)$options['watermark_font_size'] * $scale;
				$box = $this->calculateTextBox($text, $font, $fontsize, $angle);
				$watermark = imagecreatetruecolor($box['width'], $box['height']);
				list($red, $green, $blue) = explode(',', $options['color']);
				$color = imagecolorallocate($watermark, $red, $green, $blue);
				$x = $box['left'];
				$y = $box['top'];
				imagesavealpha($watermark, true);
				imagefill($watermark, 1, 1, imagecolorallocatealpha($watermark, 255, 255, 255, 127));
				imagecolortransparent($watermark, $color);
				imagettftext($watermark, $fontsize, $angle, $x, $y, $color, $font, $text);
				$watermark_width  = imagesx($watermark);
				$watermark_height = imagesy($watermark);
				break;
		}
		
		if ((float)$options['angle'] > 0) {
		
			$transparent = imagecolorallocatealpha($watermark, 200, 200, 200, 127);
			imagefill($watermark, 0, 0, $transparent);
			$watermark = imagerotate($watermark, (float)$options['angle'], $transparent);
			imagesavealpha($watermark, true);
			
			$watermark_width = imagesx($watermark);
			$watermark_height = imagesy($watermark);
		
		}
		
		$offset_x = (float)$options['offset']['x'] * $scale;
		$offset_y = (float)$options['offset']['y'] * $scale;
		
		switch($options['position']) {
			case 'topleft':
				$watermark_pos_x = $offset_x;
                $watermark_pos_y = $offset_y;
				break;
			case 'topcenter':
				$watermark_pos_x = (($info['width'] - $watermark_width) / 2);
				$watermark_pos_y = 0 + $offset_y;
				break;
			case 'topright':
				$watermark_pos_x = ($info['width'] - $watermark_width) - $offset_x;
                $watermark_pos_y = 0 + $offset_y;
				break;
			case 'middleleft':
				$watermark_pos_x = $offset_x;
				$watermark_pos_y = (($info['height'] - $watermark_height) / 2) + $offset_y;
				break;
			case 'middle':
				$watermark_pos_x = (($info['width']- $watermark_width) / 2) + $offset_x;
            	$watermark_pos_y = (($info['height'] - $watermark_height) / 2) + $offset_y;
				break;
			case 'middleright':
				$watermark_pos_x = ($info['width'] - $watermark_width) - $offset_x;
				$watermark_pos_y = (($info['height'] - $watermark_height) / 2) - $offset_y;
				break;
			case 'bottomleft':
				$watermark_pos_x = 0 + $offset_x;
                $watermark_pos_y = ($info['height'] - $watermark_height) - $offset_y;
				break;
			case 'bottomcenter':
				$watermark_pos_x = (($info['width']- $watermark_width) / 2) + $offset_x;
				$watermark_pos_y = ($info['height'] - $watermark_height) - $offset_y;
				break;
			case 'bottomright':
				$watermark_pos_x = ($info['width'] - $watermark_width) - $offset_x;
                $watermark_pos_y = ($info['height'] - $watermark_height) - $offset_y;
				break;
		}
		
		if ($options['opacity']) {
			$this->filter_opacity($watermark, $options['opacity']);
		}

		imagecopy($image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		
		if ($info['mime'] == 'image/png') {
			imagealphablending($image, false);
			imagesavealpha($image, true);
		}
		
		$info = pathinfo($filename);
		$extension = strtolower($info['extension']);
		
		if ($extension == 'jpeg' || $extension == 'jpg') {
			imagejpeg($image, $filename, 90);
		} elseif($extension == 'png') {
			imagepng($image, $filename, 0);
		} elseif($extension == 'gif') {
			imagegif($image, $filename);
		}
		
		imagedestroy($image);
	}
	
	public function calculateTextBox($text, $fontFile, $fontSize, $fontAngle) { 
		$rect = imagettfbbox($fontSize, $fontAngle, $fontFile, $text); 
		$minX = min(array($rect[0], $rect[2], $rect[4], $rect[6])); 
		$maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6])); 
		$minY = min(array($rect[1], $rect[3], $rect[5], $rect[7])); 
		$maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7])); 
		return array( 
			"left"   => 0,
			"top"    => abs($minY),
			"width"  => abs($rect[0]) + abs($rect[2]) + 1, 
			"height" => abs($rect[7]) + abs($rect[1]), 
			"box"    => $rect 
		); 
	} 
	
	private function filter_opacity(&$img, $opacity ) {
		if (!isset($opacity)) { 
			return false; 
		}
		$opacity /= 100;
		$w = imagesx($img);
		$h = imagesy($img);
		
		imagealphablending($img, false);
		
		$minalpha = 127;
		for ($x = 0; $x < $w; $x++) {
			for ($y = 0; $y < $h; $y++) {
				$alpha = ( imagecolorat( $img, $x, $y ) >> 24 ) & 0xFF;
				if ($alpha < $minalpha) { 
					$minalpha = $alpha; 
				}
			}
		}

		for ($x = 0; $x < $w; $x++ ) {
			for( $y = 0; $y < $h; $y++) {
				$colorxy = imagecolorat( $img, $x, $y );
				$alpha = ( $colorxy >> 24 ) & 0xFF;
				if ($minalpha !== 127) {
					$alpha = 127 + 127 * $opacity * ( $alpha - 127 ) / ( 127 - $minalpha ); 
				} else { 
					$alpha += 127 * $opacity; 
				}
				$alphacolorxy = imagecolorallocatealpha($img, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha);
				if (!imagesetpixel($img, $x, $y, $alphacolorxy)) {
					return false;
				}
			}
		}
		return true;
    }
}
?>