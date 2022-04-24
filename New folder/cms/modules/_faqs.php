<?
	array_push($MODULES,"faqs");
// Testimonials functions

function GetFAQs($id=0,$approved="0")
{
	global $SITE_LANGUAGE_ID;
	if ($id==0) // Get all Testimonials
	{
		$query = " 	SELECT 
						faqs.*,
						UNIX_TIMESTAMP(faqs.date_created) as date_created,
						users.name as username
					FROM 
						faqs
					LEFT JOIN users ON faqs.user_id=users.id";
		if ($approved=="1")
					$query .= " WHERE faqs.approved='1' ";
		$query .= " ORDER BY faqs.approved, date_created DESC, author"; 
	}
	else // Get provided testimonial
	{
		$query = " 	SELECT 
						faqs.*,
						UNIX_TIMESTAMP(faqs.date_created) as date_created,
						users.name as username
					FROM 
						faqs
					LEFT JOIN users ON faqs.user_id=users.id
					WHERE faqs.id='$id'"; 
	}
	$result = query($query,"s");
	return $result;
}

function GetLastFAQs($total=5)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					faqs.*,
					UNIX_TIMESTAMP(faqs.date_created) as date_created
				FROM 
					faqs
				WHERE approved='1'
				ORDER BY approved, date_created DESC
				LIMIT $total"; 
	$result = query($query,"s");
	return $result;
}


function PutFAQs($faqs, $type)
{
	global $IMAGES, $ROOT_RELATIVE, $SITE_LANGUAGE, $SITE_LANGUAGE_ID, $TEXT_VARS;
	
	if ($type=="list")
	{
		$out = "";
		foreach ($faqs as $faq)
		{
			$out .= "<a name='faq".$faq['id']."'></a><div class='faq' id='faq".$faq['id']."'>";
			$out .= "<div class='question'>".$faq['question_l'.$SITE_LANGUAGE_ID]."</div>";
			$out .= "<div class='answer'>".$faq['answer_l'.$SITE_LANGUAGE_ID]."</div>";
			$out .= "</div>";
		}
		return $out; 
	}
	elseif ($type=="short")
	{
		$out = "";
		foreach ($faqs as $faq)
		{
			$out .= "<a href='/faq/#faq".$faq['id']."' class='question'>".$faq['question_l'.$SITE_LANGUAGE_ID]."</a>";
		}
		return $out; 
	}
	elseif ($type=="full")
	{
		$out = "";
		foreach ($faqs as $faq)
		{
			$out .= "<div class='site_news_image_container'><img src='${IMAGES}faqs_marker.gif' alt='".$faq['question_l'.$SITE_LANGUAGE_ID]."'></div>";
			$out .= "<strong href='${ROOT_RELATIVE}faq/".$faq['id']."' class='site_subtitle'>".$faq['question_l'.$SITE_LANGUAGE_ID]."</strong>";
			$out .= "<br class='site_content'><br><div>".$faq['answer_l'.$SITE_LANGUAGE_ID]."</div>";
			$out .= "<br><div align='right'><em>".$faq['username']."</em></div><br>";
		}
		return $out; 
	}
}

?>