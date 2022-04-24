<?
	array_push($MODULES,"hr");
// hr functions

function GetPosition($id=0)
{
	$query = " 	SELECT DISTINCT
					hr_position.*,
					hr_position.name_l".$_SESSION['lid']." as name,
					hr_position.description_l".$_SESSION['lid']." as description
				FROM 
					hr_position ";
	if (!empty($id))
		$query .= " WHERE id='$id'"; 
	else
		$query .= " ORDER BY name "; 
		
	$result = query($query,"s");
	return $result;
}

function GetEducation($id=0)
{
	$query = " 	SELECT DISTINCT
					hr_education.*,
					hr_education.name_l".$_SESSION['lid']." as name
				FROM 
					hr_education ";
	if (!empty($id))
		$query .= " WHERE id='$id'"; 
	else
		$query .= " ORDER BY name "; 
		
	$result = query($query,"s");
	return $result;
}

function GetResume($id=0, $type="", $position_id=0, $page=0, $onpage=0, $order="date_created", $orderDir="desc", $hot="0")
{
	if ($id<0)
		$query = " 	SELECT COUNT(hr_resume.id) as total ";
	else
		$query = " 	SELECT 	hr_resume.*,
							hr_resume.city_l".$_SESSION['lid']." as city,
							hr_resume.description_l".$_SESSION['lid']." as description,
							hr_position.name_l".$_SESSION['lid']." as position_name,
							hr_position.chpu as position_chpu,
							hr_education.name_l".$_SESSION['lid']." as edu_name
		";
		
	$query .= " FROM hr_resume 
				LEFT JOIN hr_position ON hr_position.id=hr_resume.position_id
				LEFT JOIN hr_education ON hr_education.id=hr_resume.edu_id
				WHERE 1=1 ";
	
	if (!empty($position_id))
		$query .= " AND position_id='$position_id'"; 
		
	if (!empty($type))
		$query .= " AND type='$type'"; 
		
	if (!empty($hot))
		$query .= " AND hot='1'"; 
		
	if (!empty($id))
		$query .= " AND hr_resume.id='$id'"; 
	else		
		$query .= "	ORDER BY $order $orderDir"; 

	if (!empty($onpage))
		$query .= "	LIMIT ".($page*$onpage).", $onpage"; 
		
//	echo $query;
	$result = query($query,"s");
	return $result;
}


function PutArticles($articles, $type)
{
	global $IMAGES, $ROOT, $SITE_LANGUAGE, $SITE_LANGUAGE_ID, $DEFAULT_LANGUAGE_ID, $TEXT_VARS, $no_image_file, $ROOT_RELATIVE, $sape_context;
	
	if ($type=="short")
	{
		$out = "";
		foreach ($articles as $article)
		{
			$title = substr(strip_tags ($article['title'."_l$SITE_LANGUAGE_ID"]),0,70);
			$header = substr(strip_tags ($article['header']),0,150);

			if (@ empty($article['image']))
				$article['image'] = $no_image_file;

			$out .= "<a href='/articles/".$article['chpu']."/' class='art_s'><div class='title'>$title</div><div class='img'>".PutThumbnail($article['image'], 165, 100, $title, "90")."</div>";
			//$out .= "<div class='text'>$header</div>";
			$out .= "</a>";
		}
		return $out; 
	}
	elseif ($type=="index")
	{
		$out = "";
		foreach ($articles as $article)
		{
			$title = substr(strip_tags ($article['title'."_l$SITE_LANGUAGE_ID"]),0,70);
			$header = substr(strip_tags ($article['header']),0,150);

			if (@ empty($article['image']))
				$article['image'] = $no_image_file;

			$out .= "<a href='/articles/".$article['chpu']."/' class='art_c'><div class='img'>".PutThumbnail($article['image'], 59, 46, $title, "98")."</div>";
			$out .= "<div class='title'>$title</div>";
			$out .= "<div class='text'>$header</div>";
//			$out .= "<div class='date'>".date("d.m.Y", $article['date_created'])."</div>";
			$out .= "</a>";
		}
		return $out; 
	}
	elseif ($type=="list")
	{
		$out = "";
		foreach ($articles as $article)
		{
			$title = strip_tags ($article['title'."_l$SITE_LANGUAGE_ID"]);
			$text = substr(strip_tags ($article['header']),0,400);
			
			$out .= "<div class='article_c'><a href='/articles/".$article['chpu']."/' title='$title'>";
//			$out .= "<div class='img'>".PutThumbnail($article['image'], 250, 150, $title)."</div>";
			$out .= "<h3 class='title'>$title</h3>";
			$out .= "<div class='text'>$text</div>";
			$out .= "</a></div>";
		}
		return $out; 
 
	}
	elseif ($type=="full")
	{
		$out = "";
		foreach ($articles as $article)
		{
			$title = strip_tags ($article['title'."_l$SITE_LANGUAGE_ID"]);
			
//			$out .= "<h1 class='site_title'>$title</h1><br>";
//			$out .= "<div class='site_news_image_container'><img src='${IMAGES}articles_marker.gif' title='$title'></div>";

			$out .= "<div>".$article['text'."_l$SITE_LANGUAGE_ID"]."</div>";
		}
		$out .= "<br class='br'><a href='/articles/' class='more'>Другая полезная информация</a>";
		return $out; 
	}
}

?>