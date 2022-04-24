<?
	array_push($MODULES,"articles");
// articles functions

function GetArticlesCHPU($name)
{
	global $DEFAULT_LANGUAGE_ID;
	$query = " 	SELECT 
					articles.*,
					UNIX_TIMESTAMP(articles.date_created) as date_created
				FROM 
					articles
				WHERE chpu='$name'"; 
	$result = query($query,"s");
	return $result;
}

function GetArticles($id=0,$page=0,$onpage=10)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					articles.*,
					UNIX_TIMESTAMP(articles.date_created) as date_created
				FROM 
					articles ";
	if (!empty($id))
		$query .= " WHERE id='$id'"; 

	$query .= "	ORDER BY date_created DESC,id"; 

	if (!empty($page))
		$query .= "	LIMIT ".(($page-1)*$onpage).", $onpage"; 

	$result = query($query,"s");
	return $result;
}

function GetLastArticles($total=5, $not="", $order="date_created DESC")
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					id, chpu, title"."_l$SITE_LANGUAGE_ID".", UNIX_TIMESTAMP(date_created) as date_created, text"."_l$SITE_LANGUAGE_ID, header, image
				FROM 
					articles ";
					
	if (!empty($not))
		$query .= " WHERE chpu!='$not' ";

	$query .= " ORDER BY $order 
				LIMIT $total"; 
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