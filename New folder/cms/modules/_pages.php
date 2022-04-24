<?
	array_push($MODULES,"pages");
// Pages functions

function SelectPageCHPU($name)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					pages.*, modules.multiple as multiple, modules.name as name, modules.template as template
				FROM 
					pages
				LEFT JOIN modules ON modules.id=pages.module_id
				WHERE pages.chpu='$name'"; 
	$result = query($query,"s");
	return $result;
}

function SelectPage($id=0)
{
	global $SITE_LANGUAGE_ID;
	if ($id==0) // Get all pages
	{
		$query = " 	SELECT 
						pages.*, modules.multiple as multiple, modules.name as name, modules.template as template
					FROM 
						pages
					LEFT JOIN modules ON modules.id=pages.module_id
					ORDER BY pages.parent_id, pages.weight"; 
	}
	else // Get provided page
	{
		$query = " 	SELECT 
						pages.*, modules.multiple as multiple, modules.name as name, modules.template as template
					FROM 
						pages
					LEFT JOIN modules ON modules.id=pages.module_id
					WHERE pages.id='$id'"; 
	}
	$result = query($query,"s");
	return $result;
}

function SelectMenuForPage($page_name,$menu_set_id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					*
				FROM 
					menu
				WHERE href='$page_name' AND menu_set_id='$menu_set_id'"; 
	$result = query($query,"s");
	return $result;
}


function ShowPath($current, $full_path)
{
	global $SITE_LANGUAGE_ID, $PageContent, $TEXT_VARS, $template_name, $article_name, $news_name, $albums, $categories, $action_id, $action_category, $thisCategory, $worker;
	
	@ $thisPage = SelectPageCHPU($current);
	@ $parentPage = SelectPage($thisPage[0]['parent_id']);
	if ($thisPage[0]['parent_id']!=0)
	{
		$text = CutText($thisPage[0]['ptitle_l'.$SITE_LANGUAGE_ID],".",100);
		if ($thisPage[0]['chpu']==$PageContent['chpu'] && empty($thisCategory))
		{
			$full_path[] = array("title"=>$text,"url"=>"");
		}
		else
			$full_path[] =  array("title"=>$text,"url"=>"/".$_SESSION['l']."/".$thisPage[0]['chpu']."/");
			
		$full_path = ShowPath($parentPage[0]['chpu'], $full_path);
		return $full_path;
	}
	else
	{
		return array_reverse($full_path);
	}
}



function ShowPath2($current, $full_path="")
{
	global $SITE_LANGUAGE_ID, $ROOT_RELATIVE, $PageContent, $ROOT, $MODULES, $TEXT_VARS, $template_name, $article_name, $news_name, $albums, $categories, $action_id, $action_category, $thisCategory, $worker;
	
	@ $thisPage = SelectPageCHPU($current);
	@ $parentPage = SelectPage($thisPage[0]['parent_id']);
	if ($thisPage[0]['parent_id']!=0)
	{
		switch($template_name)
		{
			case "gallery":
				if (!empty($albums))
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/gallery/'>Фотогалерея</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/gallery/".$categories[0]['id']."/'>".$categories[0]['name']."</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$albums[0]['name']."</strong>";
				elseif (!empty($categories))
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/gallery/'>Фотогалерея</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$categories[0]['name']."</strong>";
				else
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>Фотогалерея</strong>";
			break;
			
			case "articles":
				if (!empty($article_name))
					$item = GetArticlesCHPU($article_name);
				if (!empty($item))
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/articles/'>Статьи</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$item[0]['title_l1']."</strong>";
				else
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>Статьи</strong>";
			break;

			case "news":
				if (!empty($news_name))
					$item = GetNewsCHPU($news_name);
				if (!empty($item))
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/news/'>Новости</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$item[0]['title']."</strong>";
				else
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>Новости</strong>";
			break;
			
			case "actions":
				if (!empty($action_id))
					$item = GetActions($action_id);
				if (!empty($item))
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href='/actions-".$action_category."/'>Объявления</a>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>".$item[0]['title_l1']."</strong>";
				else
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>Объявления</strong>";
			break;
			
			default: 
			
				$text = CutText($thisPage[0]['ptitle_l'.$SITE_LANGUAGE_ID],".",100);
				if ($thisPage[0]['chpu']==$PageContent['chpu'] && empty($thisCategory))
				{
					$full_path .= "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<strong>$text</strong>";
				}
				else
					$full_path = "&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;<a href=\"/".$_SESSION['l']."/".$thisPage[0]['chpu']."/\">$text</a>".$full_path;
				break;
		}
			
		$full_path = ShowPath2($parentPage[0]['chpu'], $full_path);
	}
	else
	{
		$full_path = "<a href=\"$ROOT\">".$TEXT_VARS['txt_Home']."</a>".$full_path;
	}
	return $full_path;
}

function SelectPageChilds($id=0)
{
	global $SITE_LANGUAGE_ID;
	$query = " 	SELECT 
					pages.*,
					modules.template
				FROM 
					pages
				LEFT JOIN modules on pages.module_id=modules.id
				WHERE pages.parent_id='$id'
				ORDER BY pages.parent_id, pages.weight"; 
	$result = query($query,"s");
	return $result;
}

function ShowPages($page_id,$level,$type="select",$current,$parent="",$disable=false)
{
	global $SITE_LANGUAGE_ID, $ROOT, $IMAGES, $CMS_ROOT;

	$sections = SelectPageChilds($page_id);

	foreach ($sections as $s)
	{
		if ($type=="cms")
		{
			$module_name = SelectModule($s['module_id']);
			$section = "<div class='map' style='padding-left:".(20*$level)."px; ".($level==1?" font-size:120%;;":($level==0?" font-size:150%;":""))."'><a href=\"".$CMS_ROOT."pages_edit.php?id=".$s['id']."\">".$s['title_l'.$SITE_LANGUAGE_ID]."</a></div>";
		}
		elseif ($type=="map")
		{
			$section = "";
			if ($s['template']!="map" && $s['chpu']!="page404")
				$section .= "<div style='padding-left:".(20*$level)."px; ".($level<2?"margin:20px 0px 0px 0px;":"")."'><a href=\"/".$_SESSION['l']."/".$s['chpu']."/\" class='".($level==0?"map1":($level==1?"map2":($level==2?"map3":"")))."'>".$s['title_l'.$SITE_LANGUAGE_ID]."</a><br></div>";
			if ($s['template']=="articles")
			{
				$Articles = GetArticles(0);
				foreach ($Articles as $f)
				{
					$section .= "<div style='padding-left:".(20+20*$level)."px;'><a href=\"".$ROOT."articles/".$f['chpu']."/\">".$f['title_l'.$SITE_LANGUAGE_ID]."</a><br></div>";
				}
			}
			elseif ($s['template']=="news")
			{
/*				$News = GetNews(0);
				foreach ($News as $f)
				{
					$section .= "<div style='padding-left:".(40+20*$level)."px;'><a href=\"".$ROOT."news/".$f['chpu']."\">".$f['title_l'.$SITE_LANGUAGE_ID]."</a><br></div>";
				}
*/				
			}
			
		}
		elseif ($type=="select")
		{
			$section="";
			if ($s['id']!=$current && !$disable)
			{
				$section = "<option value='".$s['id']."' ";
				if ($s['id']==$parent)
					$section .= " selected ";
				$section .= " >";
				for ($i=0; $i<=$level; $i++)
					$section .= "- ";
				$section .= "".substr($s['title'."_l".$SITE_LANGUAGE_ID],0,75)."</option> \n";
			}
		}
		elseif ($type=="menu")
		{

			$section = "<div class='arrow'><a href=\"";
			$section .= $ROOT.$s['chpu']."\" title='".$s['title_l'.$SITE_LANGUAGE_ID]."'>";
			$section .= substr($s['title'."_l".$SITE_LANGUAGE_ID],0,75);
			$section .= "</a></div>";
		}

		echo $section;
		
		if ($s['template']=="tour" && $type=="map")
		{
			echo "<div style='margin-left:60px;'>";
			ShowTourTypeChilds(0,0,"list",0);
			echo "</div>";
		}

		$level++;
		if ($s['id']==$current)
			$disable = true;
		ShowPages($s['id'],$level,$type,$current,$parent,$disable);
		$level--;
		if ($s['id']==$current)
			$disable = false;

	}
}


function GetIndexPage()
{
	
	global $SITE_LANGUAGE;
	$query = " 	SELECT 
					id
				FROM 
					pages
				WHERE module_id='1'"; 
	$result = query($query,"s");

	if (!empty($result[0]['id']))
		return $result[0]['id'];
	else
	{
		DropError("Индексная страница сайта не задана! Установите её!");
		exit;
	}
}

function GetPageByTemplate($tpl_name)
{
	$query = " 	SELECT 
					pages.id as id
				FROM 
					pages
				LEFT JOIN modules ON modules.id=pages.module_id
				WHERE modules.template='$tpl_name'"; 
	$result = query($query,"s");

	if (!empty($result[0]['id']))
		return $result[0]['id'];
	else
	{
		DropError("Страницы с шаблоном '$tpl_name' не существует! Установите её!");
		exit;
	}
}


function SelectModule($id=0)
{
	if ($id==0) // Get all modules
	{
		$query = " 	SELECT 
						*
					FROM 
						modules
					ORDER BY id"; 
	}
	else // Get provided module
	{
		$query = " 	SELECT 
						*
					FROM 
						modules
					WHERE id='$id'"; 
	}
	$result = query($query,"s");
	return $result;
}

function ShowModules($page_id=0, $module_id=2)
{
	global $SITE_LANGUAGE_ID, $ROOT;

	$modules = SelectModule();

	$out = "<select name='module_id' onChange='return CheckUsedModule(this, $module_id);' style='width:240px;'>";
	foreach ($modules as $module)
	{
		if ($module['template']!="cms") // disable CMS module show in drop down
		{
			@ $assigned = GetAssignedPage($module['id']);
			if (@ !empty($assigned[0]['id']) && $module['multiple']==0 )
			{
				$out .= "<option style='color:#ff0000;' value='".$module['id']."'";
				if ($module_id==$module['id'])
					$out .= " selected ";
				$out .= ">".$module['name']." (используется для \"".substr($assigned[0]['title'."_l$SITE_LANGUAGE_ID"],0,50)."\")</option>";
			}
			else
			{
				$out .= "<option value='".$module['id']."'";
				if ($module_id==$module['id'])
					$out .= " selected ";
				$out .= ">".$module['name']."</option>";
			}
		}
	}
	echo "$out</select><input type='hidden' name='chpu_to_change' value=''>";
}

function GetAssignedPage($module_id)
{
	$query = " 	SELECT DISTINCT
					*
				FROM 
					pages
				WHERE module_id='$module_id'"; 
	$result = query($query,"s");
	return $result;
}

?>