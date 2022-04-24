<?
	array_push($MODULES,"cms");

// Common functions
function HighlightText($orig_text,$words)
{
	$out_text = "";
	foreach ($words as $word)
	{
		$text = $orig_text;
		$pos = strpos (strtolower($text),strtolower($word));
		if ($pos===false)
		{
		}
		else
		{
			if (empty($out_text))
			{
			if ($pos>75)
				{
				$text = substr($text,$pos-75,150);
					$text = eregi_replace($word, "<span class='site_search_word'>$word</span>",$text); 
					$out_text .= $text;
				}
			else
				{
				$text = substr($text,0,150);
			$text = eregi_replace($word, "<span class='site_search_word'>$word</span>",$text); 
			$out_text .= $text;
		}
	}
			else
			{
				$out_text = eregi_replace($word, "<span class='site_search_word'>$word</span>",$out_text); 
			}
		}
	}
	if (strlen($out_text)==0)
	{
		$out_text = substr($text,0,150);
	}
	return $out_text;
}


function ReturnSearchResults($search,$tables='pages,news')
{
	global $SITE_LANGUAGE_ID, $TEXT_VARS, $global_car_width, $global_thumb_width, $global_thumb_height;
	$search = substr($search, 0, 64);
//	$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);	
	$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  "," $search ")));
	$good = ereg_replace(" +", " ", $good);
	$logic = "OR";

	$searchForTables = array();
	$searchForTables = explode(",",$tables);

/*
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\">
			<form name=\"searchForm\" method=\"post\" action=\"/search/\" onSubmit = \"if (this.search_request.value.length<3) {alert ('".$TEXT_VARS['search_short_request']."'); return false;}\">
				<div class=\"search_form_in\">
				<br>
				<input size='20' type=\"text\" value='$search' name=\"search_request\" class=\"search_form_input\">
				<input type=\"image\" src=\"/images/search_form_but.png\" onClick=\"this.form.submit();\" class=\"search_form_but\">
				</div>
			</form>
		</table><br><br>";
*/
	if (!empty($good))
	{
		if (strlen($good)<3)
		{
			echo $TEXT_VARS['search_short_request'];
		}
		else
		{
			if (in_array("pages",$searchForTables))
			{
				$query = "SELECT 	
							id, 
							chpu,
							title_l${SITE_LANGUAGE_ID} as title,
							content_l${SITE_LANGUAGE_ID} as text
						FROM pages 
						WHERE 
							(title_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR title_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%' OR
							content_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR content_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%')
							";
				$search_pages_results = query($query,"s");
			}		
	
			if (in_array("news",$searchForTables))
			{
				$query = "SELECT 	
							id,
							chpu, 
							title_l${SITE_LANGUAGE_ID} as title,
							text_l${SITE_LANGUAGE_ID} as text
						FROM news 
						WHERE 
							title_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR title_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%' OR
							text_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR text_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%'
							";
				$search_news_results = query($query,"s");
			}
			
			if (in_array("actions",$searchForTables))
			{
				$query = "SELECT 	
							id,
							title_l${SITE_LANGUAGE_ID} as title,
							text_l${SITE_LANGUAGE_ID} as text
						FROM actions
						WHERE 
							title_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR title_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%' OR
							text_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR text_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%'
							";
				$search_actions_results = query($query,"s");
			}
			
			if (in_array("announcements",$searchForTables))
			{
				$query = "SELECT 	
							id,
							category_id,
							title,
							text
						FROM announcements
						WHERE 
							title LIKE '%". str_replace(" ", "%' OR title LIKE '%", $good). "%' OR
							text LIKE '%". str_replace(" ", "%' OR text LIKE '%", $good). "%'
							";
				$search_announcements_results = query($query,"s");
			}
			
			if (in_array("articles",$searchForTables))
			{
				$query = "SELECT 	
							id,
							chpu, 
							title_l${SITE_LANGUAGE_ID} as title,
							text_l${SITE_LANGUAGE_ID} as text
						FROM articles 
						WHERE 
							title_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR title_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%' OR
							text_l${SITE_LANGUAGE_ID} LIKE '%". str_replace(" ", "%' OR text_l${SITE_LANGUAGE_ID} LIKE '%", $good). "%'
							";
				$search_articles_results = query($query,"s");
			}
			
			
			$total=1;
			$words = explode(" ",$good);
	
			if (in_array("pages",$searchForTables))
			{
				foreach ($search_pages_results as $res)
				{
					$title = HighlightText(strip_tags($res["title"]),$words);
					$text = HighlightText(strip_tags($res["text"]),$words);
					echo "<div class='site_search_result' >$total. <a href='/".$res["chpu"]."'><span class='site_subtitle'>$title</span><br>$text</a></div><br>";
					$total++;
				}
			}
			if (in_array("news",$searchForTables))
			{
				foreach ($search_news_results as $res)
				{
					$title = HighlightText(strip_tags($res["title"]),$words);
					$text = HighlightText(strip_tags($res["text"]),$words);
					echo "<div class='site_search_result' >$total. <a href='/news/".$res["chpu"]."'><span class='site_subtitle'>$title</span><br>$text</a></div><br>";
					$total++;
				}
			}			
			if (in_array("announcements",$searchForTables))
			{
				foreach ($search_announcements_results as $res)
				{
					$title = HighlightText(strip_tags($res["title"]),$words);
					$text = HighlightText(strip_tags($res["text"]),$words);
					echo "<div class='site_search_result' >$total. <a href='/board/".$res["category_id"]."/".$res["id"]."'><span class='site_subtitle'>$title</span><br>$text</a></div><br>";
					$total++;
				}
			}			
			if (in_array("articles",$searchForTables))
			{
				foreach ($search_articles_results as $res)
				{
					$title = HighlightText(strip_tags($res["title"]),$words);
					$text = HighlightText(strip_tags($res["text"]),$words);
					echo "<div class='site_search_result' >$total. <a href='/articles/".$res["chpu"]."'><span class='site_subtitle'>$title</span><br>$text</a></div><br>";
					$total++;
				}
			}			
			
			
			echo $TEXT_VARS['search_you_do']." <strong>\"$search\"</strong>. ";
			if ($total-1<=0)
				echo $TEXT_VARS['search_nothing_found'];
			else
				echo $TEXT_VARS['search_found'].": ".($total-1);
		}
	}

}

function CheckModuleAccess($moduleName="cms")
{
	if (@ empty($_SESSION['LoggedUser']['id']) || ! in_array($moduleName, $_SESSION['LoggedUser']['modules']))
	{
		DropError("У вас недостаточно прав для доступа к данному модулю ($moduleName)!<br><a href='javascript:history.go(-1)'>вернуться</a>");
		exit;
	}
}
	
function CheckLogin()
{
	global $TEXT_VARS;

	@ $email = mysql_real_escape_string($_POST["email"]);
	@ $password = mysql_real_escape_string($_POST["password"]);

	@ $register = mysql_real_escape_string($_GET["register"]);

	$LoggedUser = array ();
	
	if (@ empty($_SESSION['LoggedUser']['id']))
	{
		if (@ !empty($email) && !empty($password))
		{
			$query = " 	SELECT 
							u.id, 
							u.name,
							u.email,
							g.available_modules,
							g.access_level
						FROM users u 
						LEFT JOIN groups g ON g.id=u.group_id
						WHERE u.email ='".$email."' AND u.password='".$password."' AND u.approved='1'"; 
			$result = query($query,"s");
			
		
			if (@ empty($result[0]['id']))		
			{
	
				// do a sleep to make hackers life harder
				sleep (1);	
	

				$query = " 	SELECT 
								u.id, 
								u.name,
								u.email,
								u.approved,
								g.available_modules,
								g.access_level
							FROM users u 
							LEFT JOIN groups g ON g.id=u.group_id
							WHERE u.email ='".$email."'"; 
				$result = query($query,"s");
				
				if (@ empty($result[0]['id']))		
				{
					// user do not exist/ Go to auth page
					DropAlert("Такого пользователя не существует");
					echo "<script language='javascript'>location.href='/login';</script>";
				}
				elseif (@ $result[0]['approved']!="1")		
				{
					DropAlert("Вы ещё не подтвердил свой электронный адрес. Инструкции были высланы в письме на ".$result[0]['email']);
				}
				else
				{
					//incorrect login info
					DropAlert($TEXT_VARS['incorrect_login']);
				}
			}
				else
				{
					//correct login info
					// create PHPSESSID
		
					@ session_start();
				session_unregister("security_code");
					@ $LoggedUser['id'] = $result[0]['id'];
					@ $LoggedUser['name'] = $result[0]['name'];
					@ $LoggedUser['email'] = $result[0]['email'];
					@ $LoggedUser['level'] = $result[0]['access_level'];
				@ $LoggedUser['modules'] = explode(",", $result[0]['available_modules'].",");
		
					$_SESSION['LoggedUser']=$LoggedUser;
	
				}
			}
	}
	else
	{
		if ($register=="logout")
		{
			// do logout
			@ session_start();
			@ session_unset("LoggedUser");
			@ session_unset("UserCart");
			@ session_destroy();
		}
	}
}

function DropError($errorMessage="Вернитесь назад и попробуйте ещё раз.")
{
	echo "<div style='font-family: Verdana, Arial, Helvetica, sans-serif; font-size:20px; color:#3399FF; font-weight:bold; text-align:center;'>Ошибка: $errorMessage</div>";
	exit;
}

function DropAlert($errorMessage="Вернитесь назад и попробуйте ещё раз.")
{
	echo "<script language='javascript'>alert('$errorMessage'); history.go(-1);</script>";
	exit;
}


// Language functions

// Current language
function GetLanguageByName($name)
{
	
	$query = " 	SELECT 
					id
				FROM 
					languages
				WHERE name='$name'"; 
	$result = query($query,"s");

	if (@ !empty($result[0]['id']))
		return $result[0]['id'];
	else
	{
		return 0;
//		DropError("Языка с именем '$name' не существует!");
//		exit;
	}
}

function SelectLanguage($id=0)
{
	if ($id==0) // Get all languages
	{
		$query = " 	SELECT 
						*
					FROM 
						languages
					ORDER BY ind"; 
	}
	else // Get provided language
	{
		$query = " 	SELECT 
						*
					FROM 
						languages
					WHERE id='$id'"; 
	}
	$result = query($query,"s");
	return $result;
}


function PutLanguageForm($doConfirm=1)
{
	global $SITE_LANGUAGE, $REQUEST_URI;
	$lf = "<form name='language' method='post' action='$REQUEST_URI' >Язык редактирования:&nbsp;<select name='l' onChange='ConfirmLanguageSwitch(this.form,$doConfirm);'>";
	$languages = SelectLanguage();
	foreach ($languages as $lang)
	{
		$lf .= "<option value='".$lang['name']."'";
		if ($SITE_LANGUAGE==$lang['name']) 
			$lf .= " selected ";
		$lf .= ">".$lang['title']."</option>";
	}
	$lf .= "</select></form>";
	echo $lf;
}


function PutLanguageSelector()
{
	global $SITE_LANGUAGE, $IMAGES, $url_ind, $ROOT;
	if ($url_ind==0)
	{
		$page = $_SERVER['QUERY_STRING'];
	}
	else
	{
		$page = substr($_SERVER['QUERY_STRING'],3);
	}

	$languages = SelectLanguage();
	$lang_str = "";
	foreach ($languages as $language)
	{
		if ($SITE_LANGUAGE==$language['name'])
			$lang_str .= "<strong>".$language['title']."</strong> | ";
		else
			$lang_str .= "<a href='$ROOT".$language['name']."/$page'>".$language['title']."</a> | ";
	}
	echo substr($lang_str,0,strlen($lang_str)-3);

}

function GetTextVars()
{
	global $SITE_LANGUAGE_ID;
	
	$query = " 	SELECT 
					name,
					description,
					value_l${SITE_LANGUAGE_ID} as value
				FROM 
					textvars
				ORDER BY name"; 
	$result = query($query,"s");

	$text_variables = array();
	foreach ($result as $var)
	{
		$text_variables[$var['name']]=$var['value'];		
		$text_variables[$var['name']."_d_"]=$var['description'];		
	}
	return $text_variables;
}

function NoQuotes($string)
{

	$string = stripslashes($string);
	$string = mysql_real_escape_string($string);
//	$string = ereg_replace ("\"", "&quot;", $string);
//	$string = ereg_replace ("'", "&#39", $string);
	return $string;
}

function ReplaceQuotes($string)
{
	$string = ereg_replace ("\"", "&quot;", $string);
	$string = ereg_replace ("'", "&#39", $string);
	return $string;
}

function CutText($text,$symbol,$lenght)
{
	$offset = 10; // минимальная длина строки
	@ $firstPos = strpos($text,$symbol,$offset);
	@ $strLen = strlen($text);
	if ($firstPos===false || $firstPos>$lenght)
	{
		return substr($text,0,$lenght);
	}
	else
		return substr($text,0,$firstPos);
}

function rrmdir($dir) 
{
	if (is_dir($dir)) 
	{
		$objects = scandir($dir);
		foreach ($objects as $object) 
		{
			if ($object != "." && $object != "..") 
			{
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
} 

function RemoveCache($filename,$widths)
{
	$ROOT_RELATIVE;
	$allWidth=explode(",",$widths);
	foreach ($allWidth as $width)
	{
		$fname = md5(substr($filename,strrpos($filename,"/")).$width).".cache";
		$subdir = substr($fname,0,2);	
		@ unlink ($ROOT_RELATIVE."_cache/$subdir/$fname");		
	}
}
?>