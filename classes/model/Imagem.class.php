<?php
define('MAX_IMG_SIZE', 100000);
define('THUMB_JPEG', 'image/jpeg');
define('THUMB_PNG', 'image/png');
define('THUMB_GIF', 'image/gif');
define('INTERLACE_OFF', 0);
define('INTERLACE_ON', 1);

define('STDOUT', '');
define('NO_LOGO', '');
define('NO_LABEL', '');

define('POS_LEFT', 0);
define('POS_RIGHT', 1);
define('POS_CENTER', 2);
define('POS_TOP', 3);
define('POS_BOTTOM', 4);

define('E_001', 'File <b>%s</b> do not exist');
define('E_002', 'Failed reading image data from <b>%s</b>');
define('E_003', 'Cannot create the copy of <b>%s</b>');
define('E_004', 'Cannot copy the logo image');
define('E_005', 'Cannot create final image');

/**
 * Classe responsável por criar uma imagem temporária
 *
 * @author André Coura
 * @since 17/08/2008
 */
class Imagem {

	private $src_file;
	private $dest_file;
	private $dest_type;

	private $interlace;
	private $jpeg_quality;

	private $max_width;
	private $max_height;
	private $fit_to_max;

	private $logo;
	private $label;

	/**
	 * Classe de manipulação de imagens
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 * @param
	 */
	public function __construct($src_file = '') {
		$this->src_file = $src_file;
		$this->dest_file = STDOUT;
		$this->dest_type = THUMB_JPEG;

		$this->interlace = INTERLACE_OFF;
		$this->jpeg_quality = 100;

		$this->max_width = 0;
		$this->max_height = 0;
		$this->fit_to_max = FALSE;

		$this->logo['file'] = NO_LOGO;
		$this->logo['vert_pos'] = POS_TOP;
		$this->logo['horz_pos'] = POS_LEFT;

		$this->label['text'] = NO_LABEL;
		$this->label['vert_pos'] = POS_BOTTOM;
		$this->label['horz_pos'] = POS_RIGHT;
		$this->label['font'] = '';
		$this->label['size'] = 20;
		$this->label['color'] = '#000000';
		$this->label['angle'] = 0;

	}

	/**
	 *
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 */
	public function ParseColor($hex_color) {

		if (strpos($hex_color, '#') === 0)
			$hex_color = substr($hex_color, 1);

		$r = hexdec(substr($hex_color, 0, 2));
		$g = hexdec(substr($hex_color, 2, 2));
		$b = hexdec(substr($hex_color, 4, 2));

		return array (
			$r,
			$g,
			$b
		);

	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function GetImageStr($image_file) {

		if (function_exists('file_get_contents')) {
			$str = @ file_get_contents($image_file);
			if (!$str) {
				$err = sprintf(E_002, $image_file);
				trigger_error($err, E_USER_ERROR);
			}
			return $str;
		}

		$f = fopen($image_file, 'rb');
		if (!$f) {
			$err = sprintf(E_002, $image_file);
			trigger_error($err, E_USER_ERROR);
		}
		$fsz = @ filesize($image_file);
		if (!$fsz)
			$fsz = MAX_IMG_SIZE;
		$str = fread($f, $fsz);
		fclose($f);

		return $str;
	}

	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function LoadImage($image_file, & $image_width, & $image_height) {

		$image_width = 0;
		$image_height = 0;

		$image_data = $this->GetImageStr($image_file);

		$image = imagecreatefromstring($image_data);
		if (!$image) {
			$err = sprintf(E_003, $image_file);
			trigger_error($err, E_USER_ERROR);
		}

		$image_width = imagesx($image);
		$image_height = imagesy($image);

		return $image;

	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function GetThumbSize($src_width, $src_height) {

		if($src_width < $this->max_width || $this->max_width == 0)
			$this->max_width = $src_width;
		if($src_height < $this->max_height || $this->max_height == 0)
			$this->max_height = $src_height;


		$max_width = $this->max_width;
		$max_height = $this->max_height;

		$x_ratio = $max_width / $src_width;
		$y_ratio = $max_height / $src_height;

		$is_small = ($src_width <= $max_width && $src_height <= $max_height);

		if (!$this->fit_to_max && $is_small) {
			$dest_width = $src_width;
			$dest_height = $src_height;
		}
		elseif ($x_ratio * $src_height < $max_height) {
			$dest_width = $max_width;
			$dest_height = ceil($x_ratio * $src_height);
		} else {
			$dest_width = ceil($y_ratio * $src_width);
			$dest_height = $max_height;
		}

		return array (
			$dest_width,
			$dest_height
		);

	}
	/**
	 *
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 */
	public function AddLogo($thumb_width, $thumb_height, & $thumb_img) {

		extract($this->logo);

		$logo_image = $this->LoadImage($this->file, $this->logo_width, $this->logo_height);

		if ($this->vert_pos == POS_CENTER) {
			$y_pos = ceil($thumb_height / 2 - $this->logo_height / 2);
			$y_pos = $thumb_height - $this->logo_height;
		} else {
			$y_pos = 0;
		}
		if ($this->horz_pos == POS_CENTER)
			$x_pos = ceil($thumb_width / 2 - $this->logo_width / 2);
		elseif ($this->horz_pos == POS_RIGHT) $x_pos = $this->thumb_width - $this->logo_width;
		else
			$x_pos = 0;

		if (!imagecopy($thumb_img, $logo_image, $x_pos, $y_pos, 0, 0, $this->logo_width, $lthis->ogo_height))
			trigger_error(E_004, E_USER_ERROR);

	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function AddLabel($thumb_width, $thumb_height, & $thumb_img) {

		extract($this->label);

		list ($r, $g, $b) = $this->ParseColor($this->color);
		$color_id = imagecolorallocate($thumb_img, $r, $g, $b);

		$text_box = imagettfbbox($this->size, $this->angle, $this->font, $this->text);
		$text_width = $text_box[2] - $text_box[0];
		$text_height = abs($text_box[1] - $text_box[7]);

		if ($this->vert_pos == POS_TOP)
			$y_pos = 5 + $text_height;
		elseif ($this->vert_pos == POS_CENTER) $y_pos = ceil($thumb_height / 2 - $text_height / 2);
		elseif ($this->vert_pos == POS_BOTTOM) $y_pos = $thumb_height - $text_height;

		if ($this->horz_pos == POS_LEFT)
			$x_pos = 5;
		elseif ($this->horz_pos == POS_CENTER) $x_pos = ceil($thumb_width / 2 - $text_width / 2);
		elseif ($this->horz_pos == POS_RIGHT) $x_pos = $thumb_width - $text_width -5;

		imagettftext($this->thumb_img, $this->size, $this->angle, $x_pos, $y_pos, $color_id, $this->font, $this->text);

	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function OutputThumbImage($dest_image) {

		imageinterlace($dest_image, $this->interlace);

		header('Content-type: ' . $this->dest_type);

		if ($this->dest_type == THUMB_JPEG)
			imagejpeg($dest_image, '', $this->jpeg_quality);
		elseif ($this->dest_type == THUMB_GIF) imagegif($dest_image);
		elseif ($this->dest_type == THUMB_PNG) imagepng($dest_image);

	}
	/**
	 *
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 */
	public function SaveThumbImage($image_file, $dest_image) {

		imageinterlace($dest_image, $this->interlace);

		if ($this->dest_type == THUMB_JPEG)
			imagejpeg($dest_image, $this->dest_file, $this->jpeg_quality);
		elseif ($this->dest_type == THUMB_GIF) imagegif($dest_image, $this->dest_file);
		elseif ($this->dest_type == THUMB_PNG) imagepng($dest_image, $this->dest_file);

	}
	/**
	 *
	 *
	 * @author André Coura
	 * @since 02/05/2007
	 */
	public function Output() {

		$src_image = $this->LoadImage($this->src_file, $this->src_width, $this->src_height);

		$dest_size = $this->GetThumbSize($this->src_width, $this->src_height);

		$dest_width = $dest_size[0];
		$dest_height = $dest_size[1];

		$dest_image = imagecreatetruecolor($dest_width, $dest_height);
		if (!$dest_image)
			trigger_error(E_005, E_USER_ERROR);

		imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dest_width, $dest_height, $this->src_width, $this->src_height);

		if ($this->logo['file'] != NO_LOGO)
			$this->AddLogo($dest_width, $dest_height, $dest_image);

		if ($this->label['text'] != NO_LABEL)
			$this->AddLabel($dest_width, $dest_height, $dest_image);

		if ($this->dest_file == STDOUT)
			$this->OutputThumbImage($dest_image);
		else
			$this->SaveThumbImage($this->dest_file, $dest_image);

		imagedestroy($src_image);
		imagedestroy($dest_image);

	}
	/**
		 *
		 *
		 * @author André Coura
		 * @since 02/05/2007
		 */
	public function setArquivoImg($imagem) {
		$this->src_file = $imagem;
	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function setDetinoArquivo($destino) {
		$this->dest_file = $destino;
	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function setDetinoType($tipo) {
		$this->dest_type = $tipo;
	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function setLargura($largura) {
		$this->max_width = $largura;
	}
	/**
	*
	*
	* @author André Coura
	* @since 02/05/2007
	*/
	public function setAltura($altrua) {
		$this->max_height = $altrua;
	}

	public function newThumb() {

		$src_image = $this->LoadImage($this->src_file, $this->src_width, $this->src_height);
		$dest_size = $this->GetThumbSize($this->src_width, $this->src_height);
		$dest_width = $dest_size[0];
		$dest_height = $dest_size[1];
		$dest_image = imagecreatetruecolor($this->max_width, $this->max_height);
		if (!$dest_image)
			trigger_error(E_005, E_USER_ERROR);
		if($this->src_width < $this->max_width || $this->max_width == 0)
			$this->max_width = $this->src_width;
		if($this->src_height < $this->max_height || $this->max_height == 0)
			$this->max_height = $this->src_height;

		$wm = $this->src_width / $this->max_width;
		$hm = $this->src_height / $this->max_height;
		$h_height = $this->max_height / 2;
		$w_height = $this->max_width / 2;
		if ($this->src_width > $this->src_height) {
			$adjusted_width = $this->src_width / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dest_image, $src_image, - $int_width, 0, 0, 0, $adjusted_width, $this->max_height, $this->src_width, $this->src_height);
		}
		elseif (($this->src_width < $this->src_height) || ($this->src_width == $this->src_height)) {
			$adjusted_height = $this->src_height / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($dest_image, $src_image,  0, - $int_height, 0, 0, $this->max_width, $adjusted_height, $this->src_width, $this->src_height);
		} else {
			imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dest_width, $dest_height, $this->src_width, $this->src_height);
		}

		if ($this->logo['file'] != NO_LOGO)
			$this->AddLogo($dest_width, $dest_height, $dest_image);

		if ($this->label['text'] != NO_LABEL)
			$this->AddLabel($dest_width, $dest_height, $dest_image);

		if ($this->dest_file == STDOUT)
			$this->OutputThumbImage($dest_image);
		else
			$this->SaveThumbImage($this->dest_file, $dest_image);
		imagedestroy($src_image);
		imagedestroy($dest_image);
	}

}
?>