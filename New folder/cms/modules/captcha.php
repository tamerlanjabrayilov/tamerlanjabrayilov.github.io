<?php
session_start();

class CaptchaSecurityImages {

//	var $font = 'timesi.ttf';
//	var $font = $_SERVER['DOCUMENT_ROOT']."/cms/modules/verdana.ttf";
	var $font = "../../cms/modules/verdana.ttf";

	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
//		$possible = '23456789abcdefghjkmnpqrstvwxyz'; 
		$possible = '1234567890'; 
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function CaptchaSecurityImages($width='60',$height='30',$characters='3') {
		$code = $this->generateCode($characters);
		/* font size will be 50% of the image height */
		$font_size = $height * 0.6;
		$image = @ImageCreateTrueColor($width, $height) or die('Cannot Initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 0xff, 0xff, 0xff);
		$text_color = imagecolorallocate($image, 0xe9, 0x43, 0x4a);
		$noise_color = $background_color; //imagecolorallocate($image, 0xff, 0xff, 0xff);
		imagefill($image, 1, 1, $background_color); 
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code);
		$x = 5;
		$y = ($height - $textbox[5])/2-3;
		for ($i=0; $i<strlen($code); $i++)
		{
//			$text_color = imagecolorallocate($image, mt_rand(1,200), mt_rand(1,200), mt_rand(1,200));
			imagettftext($image, $font_size, mt_rand(-25,25), $x+($i*$font_size), $y, $text_color, $this->font , $code[$i]);		
//			imagettftext($image, $font_size, 0, $x+($i*$font_size), $y, $text_color, $this->font , $code[$i]);		
		}
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/50; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 3, 3, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/100; $i++ ) {
//			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* output captcha image to browser */
		imagejpeg($image, '', 100);
		imagedestroy($image);
		$_SESSION['security_code'] = $code;
	}

}

//$width = isset($_GET['width']) ? $_GET['width'] : '50';
//$height = isset($_GET['height']) ? $_GET['height'] : '28';
//$characters = isset($_GET['character']) ? $_GET['character'] : '4';

$width = '60';
$height = '30';
$characters = '3';

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); 
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header('Pragma: no-cache');
header('Content-Type: image/jpeg');
$captcha = new CaptchaSecurityImages($width,$height,$characters);

?>