<?
	array_push($MODULES,"banners");
// Banners functions

function GetBanners($id=0, $onlyActive=false)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					banners 
				WHERE 1=1 ";
	if (!empty($id))
		$query .= " 	AND id='$id' ";
	
	if ($onlyActive)
		$query .= " AND status='1' ";
	
	$query .= " ORDER BY code"; 

	$result = query($query,"s");
	return $result;
}


function PutBanner($code=0,$type="image",$class="")
{
	global $SITE_LANGUAGE_ID, $no_image_file,$ROOT;
	$query = " 	SELECT DISTINCT
					*
				FROM 
					banners 
				WHERE code='$code' ";
	$result = query($query,"s");
	@ $banner = $result[0];

	if (!empty($banner['image']) && $banner['image']!=$no_image_file)
	{
		return "<div class='$class'><div><a href='".$banner['url']."' title='".$banner['title_l'."$SITE_LANGUAGE_ID"]."'>".PutImage($banner['image'], $banner['width'], $banner['height'], $banner['title_l'."$SITE_LANGUAGE_ID"], $banner['url'], $banner['id'])."</a></div></div>";
	}

}


?>