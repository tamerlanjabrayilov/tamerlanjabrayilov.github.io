<?php
class CreateThumbnail 
{
	function CreateThumbnail($width,$height,$imagefile,$quality,$mode,$cachefile="") 
	{
		if (!file_exists($imagefile))
			$imagefile = "../../userfiles/no_image.jpg";

		$ext = mb_strtolower(substr($imagefile, 1 + strrpos($imagefile, ".")),"windows-1251");
		if ($ext=="png")
			$src_image = @imagecreatefrompng ($imagefile);
		elseif ($ext=="gif")
			$src_image = @imagecreatefromgif ($imagefile);
		elseif ($ext=="jpg" || $ext=="jpeg")
			$src_image = @imagecreatefromjpeg ($imagefile);
		else
			$imagefile = "../../userfiles/no_image.jpg";
			
		$src_image_width = imagesx($src_image);
		$src_image_height = imagesy($src_image);
		
		@ $ratio = $src_image_height / $src_image_width;
		if ($height==1)
		{
			$width = $width;
			$height = $width * $ratio;
		}		

		if ($mode=="crop")
		{
			// операции для получения файла 
			// создаём пустую картинку 
			// важно именно truecolor!, иначе будем иметь 8-битный результат 
			// вырезаем серединку по x, если фото горизонтальное 
			if ($src_image_width>$src_image_height)
			{ 
				$dst_image = imagecreatetruecolor($width,$height); 
				imagefill ($dst_image, 1, 1, imagecolorallocate($dst_image, 0xfc, 0xf9, 0xee));
				
				$new_ratio = $width / $height;
				$src_ratio = $src_image_width / $src_image_height;
				if ($new_ratio<$src_ratio)
				{
					$new_y = $src_image_height;
					$new_x = $src_image_height*$new_ratio;
					imagecopyresampled($dst_image, $src_image, 0, 0, round(($src_image_width-$new_x)/2), 0, $width, $height, $new_x, $new_y); 
				}
				else
				{
					$new_x = $src_image_width;
					$new_y = $src_image_width/$new_ratio;
					imagecopyresampled($dst_image, $src_image, 0, 0, 0, round(($src_image_height-$new_y)/2), $width, $height, $new_x, $new_y); 
				}
				
			}
			
			// если фото вертикальное
			if ($src_image_width<$src_image_height)
			{
				$dst_image = imagecreatetruecolor($width,$height); 
				imagefill ($dst_image, 1, 1, imagecolorallocate($dst_image, 0xfc, 0xf9, 0xee));
				
				$new_width = $height / $ratio;
				$offset_x = round(($new_width-$width)/2);
				imagecopyresampled($dst_image, $src_image, -$offset_x, 0, 0, 0, $new_width, $height, $src_image_width, $src_image_height); 
			}
			
			// квадратная картинка масштабируется без вырезок 
			if ($src_image_width==$src_image_height) 
			{
				$dst_image = imagecreatetruecolor($width,$height); 
				imagefill ($dst_image, 1, 1, imagecolorallocate($dst_image, 0xfc, 0xf9, 0xee));
				
				imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $width, $src_image_width, $src_image_width); 
			}
		}
		elseif($mode=="fit") // картинка должна влезть полюбому, центруем по высоте и ширине
		{
			$dst_image = imagecreatetruecolor($width,$height); 
			imagefill ($dst_image, 1, 1, imagecolorallocate($dst_image, 0xfc, 0xf9, 0xee));

			$new_width = $new_height = 0;
			$offset_x = $offset_y = 0;
			
			if ($src_image_width>$src_image_height)
			{
				$new_width = $width;
				$offset_x = 0;
				
				$new_height = $ratio * $new_width;
				$offset_y = round(($height-$new_height)/2);
			}
			else
			{
				$new_height = $height;
				$offset_y = 0;
				
				$new_width = $new_height/$ratio;
				$offset_x = round(($width-$new_width)/2);
			}
			imagecopyresampled($dst_image, $src_image, $offset_x, $offset_y, 0, 0, $new_width, $new_height, $src_image_width, $src_image_height); 
			
		}

		// output new image to browser 
		// output new image to browser
		if (!empty($cachefile))
		{
			$subdir = substr($cachefile,0,2);
			imagejpeg($dst_image,"../../_cache/$subdir/".$cachefile,$quality);
		}
		else
			imagejpeg($dst_image,'',$quality);
			
		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
}

$width = isset($_GET['w']) ? $_GET['w'] : '120';
$height = isset($_GET['h']) ? $_GET['h'] : '1';
$imagefile = isset($_GET['i']) ? $_GET['i'] : '';
$quality = isset($_GET['q']) ? $_GET['q'] : '90';
$mode = isset($_GET['m']) ? $_GET['m'] : 'crop';


$remakeCache = isset($_GET['c']) ? $_GET['c'] : '';

$settings_cachedir = "../../_cache"; //каталог кэша
$settings_cachetime = 2592000; //время жизни кэша (30 суток)

header('Content-Type: image/jpeg');

/*
$fname = md5(substr($imagefile,strrpos($imagefile,"/")).$width).".cache";
$subdir = substr($fname,0,2);

if (empty($remakeCache))
{
	if (file_exists("$settings_cachedir/$subdir/$fname"))
	{
		$ftime = filemtime("$settings_cachedir/$subdir/$fname");
		if ((time() - $settings_cachetime) < $ftime) 
		{
			readfile("$settings_cachedir/$subdir/$fname");
			exit;
		}
	}
}

// create cache
if (!is_dir("$settings_cachedir/$subdir/"))
	mkdir("$settings_cachedir/$subdir/", 0777, true);	

$thumb = new CreateThumbnail($width,$height,$imagefile,$quality,$mode,$fname);
readfile("$settings_cachedir/$subdir/$fname");
*/
$thumb = new CreateThumbnail($width,$height,$imagefile,$quality,$mode);
?>