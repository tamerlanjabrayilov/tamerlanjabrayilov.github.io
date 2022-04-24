<?
	array_push($MODULES,"albums");
// Albums functions

function SelectAlbum($id=0, $category_id=0,$limit=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					albums.*,
					UNIX_TIMESTAMP(albums.date_created) as date_created,
					albums_category.name as category_name
				FROM 
					albums 
				LEFT JOIN albums_category ON albums.category_id=albums_category.id
				WHERE 1=1 ";
	if (!empty($category_id))
		$query .= " AND albums.category_id='$category_id' ";
		
	if (!empty($id))
		$query .= " AND albums.id='$id' ";
	else
		$query .= " ORDER BY albums.date_created DESC, albums.name"; 
	if (!empty($limit))
		$query .= " LIMIT $limit ";
	$result = query($query,"s");
	return $result;
}

function SelectAlbumChilds($id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					albums
				WHERE parent_id='$id'
				ORDER BY parent_id, weight, name_l${SITE_LANGUAGE_ID}"; 
	$result = query($query,"s");
	return $result;
}

function ShowAlbum($album_id,$level,$type="cms",$current,$parent="",$disable=false)
{
	global $ROOT, $IMAGES, $CMS_ROOT, $global_thumb_width,$global_thumb_height, $SITE_LANGUAGE_ID, $TEXT_VARS;

	if ($type=="full")
	{
		$album = GetAlbum($album_id);

		$photos = array();
		ShowAlbum($album['id'],0,"tree",$album['id']);

		$photos = GetAlbumContent($current);

		if (@ sizeof($photos)>0)
		{
			$count = 1;
//			echo "<table border='0' align='center' width='100%' cellpadding='0' cellspacing='0'><tr><td colspan='3'><h1 class='site_title'>".$album['name'."_l$SITE_LANGUAGE_ID"]."</h1><br></td></tr><tr>";
			echo "<table border='0' align='center' width='590' cellpadding='0' cellspacing='0'><tr><td colspan='3'></td></tr><tr>";
			foreach ($photos as $photo)
			{
				if ($count%4==0)
				{
					echo "</tr><tr><td colspan='3' height='20'></td></tr><tr>";
					$count = 1;
				}
				echo "<td width='33%' align='center' valign='top'><a href='".$ROOT.$photo['imagefile']."' rel='lightbox' title='".$photo['description'."_l$SITE_LANGUAGE_ID"]."'>";
				echo PutThumbnail($photo['imagefile'],$global_thumb_width,$global_thumb_height,$photo['description'."_l$SITE_LANGUAGE_ID"],"80");
				echo "</a><br><div class='album'>".$photo['description'."_l$SITE_LANGUAGE_ID"]."</div></td>";
				$count++;
			}
	
			for ($i=$count; $i<=3; $i++)
				echo "<td width='33%'>&nbsp;</td>";
//			echo "<tr><td colspan='3'><div class='more'><a href='/gallery'>".$TEXT_VARS['back']."</a></div></td></tr>";
			echo "</table>";
		}				
	}
	else
	{
		$albums = SelectAlbumChilds($album_id);
		foreach ($albums as $album)
		{
			if ($type=="tree")
			{
				$size = GetAlbumSize($album['id']);
				if ($size>0)
					$size = "($size)";
				else
					$size = "";
				echo "<table align='center' border='0' cellspacing='0' cellpadding='5' width='100%'><tr><td valign='middle' style='padding-left:".(15*$level)."px;'></td><td width='100%'><a href=\"".$ROOT."gallery/".$album['id']."\" class='site_subtitle'>".$album['name'."_l$SITE_LANGUAGE_ID"]." $size </a></td></tr></table>";
			}
			elseif ($type=="cms")
			{
				echo "<div class='album_item' style='margin-left:".(10*$level)."px;'><a href=\"".$CMS_ROOT."albums_edit.php?id=".$album['id']."\">".$album['name'."_l$SITE_LANGUAGE_ID"]."</a></div>";
			}
			elseif ($type=="select")
			{
				if ($album['id']!=$current && !$disable)
				{
					$albumz = "<option value='".$album['id']."' ";
					if ($album['id']==$parent)
						$albumz .= " selected ";
					$albumz .= " >";
					for ($i=0; $i<=$level; $i++)
						$albumz .= "- ";
					$albumz .= $album['name'."_l$SITE_LANGUAGE_ID"]."</option>";
					echo $albumz;
				}
			}

			$level++;
			if ($album['id']==$current)
				$disable = true;
			ShowAlbum($album['id'],$level,$type,$current,$parent,$disable);
			$level--;
			if ($album['id']==$current)
				$disable = false;
		}
	}
}

function GetAlbumContent($id)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					im.id,
					im.imagefile,
					im.description_l${SITE_LANGUAGE_ID},
					im.date_created
				FROM 
					images as im
				LEFT JOIN images_albums as imal ON imal.image_id = im.id
				LEFT JOIN albums as al ON al.id = imal.album_id
				WHERE al.id='$id'
				ORDER BY im.date_created"; 
	$result = query($query,"s");
	return $result;
}

function GetAlbumSize($id)
{
	$query = " 	SELECT COUNT(im.id)
				FROM 
					images as im
				LEFT JOIN images_albums as imal ON imal.image_id = im.id
				LEFT JOIN albums as al ON al.id = imal.album_id
				WHERE al.id='$id'"; 
	$result = query($query,"s");
	@ $result = $result[0][0];
	return $result;
}

function GetAlbum($id)
{
	$query = " 	SELECT 
					*
				FROM 
					albums
				WHERE id='$id'"; 
	$result = query($query,"s");
	@ $result = $result[0];
	return $result;
}

?>