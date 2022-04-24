<?

// images functions

function GetImages($file="")
{
	global $no_image_file, $SITE_LANGUAGE_ID;
	if ($file=="") // Get all images
	{
		$query = " 	SELECT 
						*
					FROM 
						images
					ORDER BY imagefile, date_created"; 
		$result = query($query,"s");
	}
	else // Get provided image
	{
		$query = " 	SELECT DISTINCT
						*
					FROM 
						images
					WHERE imagefile='$file'"; 
		$result = query($query,"s");
		if (@ $result[0]['imagefile']=="")
		{
			$result[0]['imagefile']="$no_image_file";
			$result[0]['description_l'.$SITE_LANGUAGE_ID]="";
		}
	}
	return $result;
}


function CreateThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $quality="90",$imageTmbName="", $checkWidth="1")
{
	global $SITE_LANGUAGE_ID, $ROOT_RELATIVE, $IMAGES;

	$file = pathinfo($imageName);
	$imageDirectory = $file['dirname'];
	$imageName = $file['basename'];
	$imageExt = $file['extension'];

	if ($imageExt == "swf")
	{
		$imageTmbName = Translit::UrlTranslit(substr($imageName,0,strlen($imageName)-strlen($imageExt)-1), TR_NO_SLASHES).".$imageExt";
		copy($imageDirectory."/".$imageName, $_SERVER['DOCUMENT_ROOT']."/$thumbDirectory/$imageTmbName");
		return "$thumbDirectory/$imageTmbName";
	}
	
	if (empty($imageTmbName))
		$imageTmbName = Translit::UrlTranslit(substr($imageName,0,strlen($imageName)-strlen($imageExt)-1), TR_NO_SLASHES);
	else
	{
		$ext_ = strtolower(substr($imageTmbName, 1 + strrpos($imageTmbName, ".")));
		$imageTmbName = Translit::UrlTranslit(substr($imageTmbName,0,strlen($imageTmbName)-strlen($ext_)-1), TR_NO_SLASHES);
	}

	$imageTmbName = str_replace(".","-",$imageTmbName);
	$imageTmbName = str_replace("+","plus",$imageTmbName);
	$imageTmbName = str_replace("&","-",$imageTmbName);

	$add="";
	clearstatcache();
	while (is_file ($_SERVER['DOCUMENT_ROOT']."/${thumbDirectory}/${imageTmbName}${add}.jpg"))
	{
		clearstatcache();
		$add = ($add*1)+1;
	}
	$imageTmbName = $imageTmbName.$add.".jpg";
	
	switch ($ext)
	{
		case ($ext =="jpeg" || $ext=="jpg"):
			$srcImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/$imageDirectory/$imageName");
		break;
		case ($ext =="gif"):
			$srcImg = imagecreatefromgif($_SERVER['DOCUMENT_ROOT']."/$imageDirectory/$imageName");
		break;
		case ("png"):
			$srcImg = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/$imageDirectory/$imageName");
		break;
	}

	$origWidth = imagesx($srcImg);
	$origHeight = imagesy($srcImg);
	
	if ($checkWidth)
	{
		if ($origWidth>=$origHeight)
		{
			$ratio = $origWidth / $thumbWidth;
			if ($ratio<=1)
			{
				$thumbWidth = $origWidth;
				$thumbHeight = $origHeight;
			}
			else
				$thumbHeight = $origHeight / $ratio;
		}
		else
		{
			$thumbHeight = $thumbWidth;
			
			$ratio = $origHeight / $thumbHeight;
			if ($ratio<=1)
			{
				$thumbWidth = $origWidth;
				$thumbHeight = $origHeight;
			}
			else
				$thumbWidth = $origWidth / $ratio;
		}
	}
	else
	{
		$ratio = $origWidth / $thumbWidth;
		if ($ratio<=1)
		{
			$thumbWidth = $origWidth;
			$thumbHeight = $origHeight;
		}
		else
			$thumbHeight = $origHeight / $ratio;
	}


	$thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
	imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, imagesx($thumbImg), imagesy($thumbImg), $origWidth, $origHeight);
	
	
	// add watermark
//	$watermark = imagecreatefrompng($IMAGES."watermark.png");
//	$watermark_width = imagesx($watermark);  
//	$watermark_height = imagesy($watermark);  
//	$dest_x = imagesx($thumbImg) - $watermark_width - 5;  
//	$dest_y = imagesy($thumbImg) - $watermark_height - 5;  
//	@ imagecopymerge($thumbImg, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 30);  
		
	imagejpeg($thumbImg, $_SERVER['DOCUMENT_ROOT']."/$thumbDirectory/$imageTmbName",$quality);
	@ ImageDestroy($thumbImg);
	
	return "$thumbDirectory/$imageTmbName";
}



function PutThumbnail($file, $width, $height, $description="", $quality="90", $mode="crop")
{
	global $CMS_ROOT, $ROOT, $no_image_file, $ROOT_RELATIVE;
	$ext = substr($file, 1 + strrpos($file, "."));
		
	if (empty($file))
		$file = $no_image_file;
		
	return "<img alt='$description' title='$description' src='/cms/modules/thumb.php?w=$width&h=$height&q=$quality&m=$mode&i=../../$file' />";
}

function PutImage($file, $width, $height, $description="", $url="", $banner_id=0)
{
	global $ROOT,$no_image_file,$SITE_LANGUAGE;
	
	if (empty($file))
		$file = $no_image_file;
	
	$ext = substr($file, 1 + strrpos($file, "."));
		
	if ($ext=="swf")
	{				
		return "<div id=\"banner-$banner_id\"><noindex>У вас не установлен Adobe Flash Player или отключена поддержка JavsScript <br><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></noindex></div><script>
					var flashvars = {link: \"$url\"};
					swfobject.embedSWF(\"$ROOT$file\", \"banner-$banner_id\", \"$width\", \"$height\", \"8\", null, flashvars);
				</script>";
	}
	else
	{
		if (!empty($height))
			$height="height=\"$height\"";
		else
			$height = "";

		if (!empty($width))
			$width="width=\"$width\"";
		else
			$height = "";

		return "<img alt='$description' title='$description' $width $height src='$ROOT$file'>";
	}
}

?>