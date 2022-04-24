<?
	array_push($MODULES,"news");
// news functions

function GetNewsCHPU($name)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					news.id, 
					news.title_l${SITE_LANGUAGE_ID} as title, 
					UNIX_TIMESTAMP(news.date_created) as date_created, 
					news.text_l${SITE_LANGUAGE_ID} as text, 
					news.header_l${SITE_LANGUAGE_ID} as header, 
					news.image, 
					news.chpu, 
					news.category_id,
					UNIX_TIMESTAMP(news.date_created) as date_created,
					news_category.name_l${SITE_LANGUAGE_ID} as category_name
				FROM 
					news
				LEFT JOIN news_category ON news_category.id=news.category_id
				WHERE news.chpu='$name'"; 
	$result = query($query,"s");
	return $result;
}

function GetNews($id=0,$page=0,$onpage=10)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					news.*,
					UNIX_TIMESTAMP(news.date_created) as date_created
				FROM 
					news ";
	if (!empty($id))
		$query .= " WHERE news.id='$id'"; 

	$query .= "	ORDER BY news.date_created DESC, news.id"; 

	if (!empty($page))
		$query .= "	LIMIT ".(($page-1)*$onpage).", $onpage"; 

	$result = query($query,"s");
	return $result;
}

function GetLastNews($total=5,$category_id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					id, title_l${SITE_LANGUAGE_ID} as title, UNIX_TIMESTAMP(date_created) as date_created, text_l${SITE_LANGUAGE_ID} as text, header_l${SITE_LANGUAGE_ID} as header, image, chpu, category_id
				FROM 
					news
				WHERE DATE_ADD(news.date_created, INTERVAL 30 DAY)>=NOW()";

	if (!empty($category_id))
		$query .= " AND category_id='$category_id' ";
		
	$query .= " 	ORDER BY date_created DESC ";
	if ($total>0)
		$query .= " LIMIT $total"; 
	$result = query($query,"s");
	return $result;
}


function PutNews($news, $type)
{
	global $CMS_ROOT, $ROOT, $ROOT_RELATIVE, $IMAGES, $no_image_file, $global_thumb_width, $global_thumb_height, $global_image_width, $global_image_height, $SITE_LANGUAGE, $SITE_LANGUAGE_ID, $DEFAULT_LANGUAGE_ID, $TEXT_VARS, $PageContent;

	if ($type=="list")
	{
		foreach ($news as $newz)
		{
			$title = strip_tags ($newz['title_l1']);
			$text = substr(strip_tags ($newz['header_l1']),0,500);
			
			$out .= "<div class='article_c'><a href='/news/".$newz['chpu']."/' title='$title'>";
			if (@ empty($newz['image']) || $newz['image'] == $no_image_file)
			{}
			else
				$out .= "<div class='img'>".PutThumbnail($newz['image'], 250, 150, $title)."</div>";
			$out .= "<h3 class='title'>$title</h3>";
			$out .= "<div class='text'>$text</div>";
			$out .= "</a></div>";

		}
		return $out; 
	}
	elseif ($type=="full")
	{
		$out = "";
		foreach ($news as $newz)
		{
			$title = strip_tags ($newz['title']);
			$out .= "<h1 class='site_title'>$title</h1>";
//			$out .= "<div class='news_date'>".date("d.m.Y", $newz['date_created'])."</div><br>";
			$out .= "<div>".$newz['text']."</div>";
		}
		$out .= "<br><div><a class='more' href='/news/'>Другие новости</a></div>";
		return $out; 
	}
	elseif ($type=="short")
	{
		$out = "";
		foreach ($news as $newz)
		{
			$title = strip_tags ($newz['title']);
			$text = substr(strip_tags ($newz['header']),0,100);
			
			$out .= "<div class='news_i'>";
			$out .= "<a class='title' href='/news/".$newz['chpu']."/'>$title</a>";
//			$out .= "<div class='text'>$text</div>";
			$out .= "<div class='date'>".date("d.m.Y", $newz['date_created'])."</div>";
			$out .= "</div>";
		}
		return $out; 
	}
}

function GetNewsCategory($id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					id, 
					name_l${SITE_LANGUAGE_ID} as name
				FROM 
					news_category ";
	if (!empty($id))
		$query .= "	WHERE id='$id'"; 
	else
	$query .= "	ORDER BY name_l${SITE_LANGUAGE_ID} DESC"; 
	$result = query($query,"s");
	return $result;
}

?>