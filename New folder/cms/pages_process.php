<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
	
@ 	$module_id = $_POST['module_id'];
@ 	$parent_id = $_POST['parent_id'];
@ 	$weight = $_POST['weight'];

@ 	$title = $_POST['title'];
	$title = NoQuotes($title);

@ 	$chpu = Translit::UrlTranslit($_POST['chpu'], TR_NO_SLASHES);

@	$existPage = SelectPageCHPU($chpu);
@	$existPageId = $existPage[0]['id'];

@ 	$ptitle = $_POST['ptitle'];
	$ptitle = NoQuotes($ptitle);
@ 	$description = $_POST['description'];
@ 	$keywords = $_POST['keywords'];
	$keywords = NoQuotes($keywords);

@ 	$content1 = $_POST['content'];
	$content1 = str_replace("'", "&#039;", $content1);
@ 	$content2 = $_POST['content2'];
	$content2 = str_replace("'", "&#039;", $content2);

@ 	$access_level = $_POST['access_level'];

	if ($id=="new")
	{
		if (!empty($existPageId))
			DropError("Страница с таким ЧПУ уже существует! В системе не может быть двух одинаковых ЧПУ.<br><a href='javascript:history.back();'>вернуться и исправить</a>");
	
		$weight++; 

		$query = " 	UPDATE pages SET
						weight = weight+1
					WHERE parent_id = $parent_id AND weight>=$weight"; 
		query($query,"u");


		$languages = SelectLanguage();
		$query_fields = "";
		$query_values = "";
		foreach ($languages as $lang)
		{
			$query_fields .= ",title_l".$lang['ind'].",ptitle_l".$lang['ind'].",description_l".$lang['ind'].",keywords_l".$lang['ind'].",content_l".$lang['ind'].",content2_l".$lang['ind'];
			$query_values .= ",'$title','$ptitle','$description','$keywords','$content1','$content2'";
		}
		$query = " 	INSERT INTO pages
						(module_id, parent_id, weight, chpu, access_level $query_fields)
					VALUES
						('$module_id', '$parent_id', '$weight', '$chpu', '$access_level' $query_values)"; 
		query($query,"i");
		$location = "pages_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			if (!empty($existPageId) && $existPageId!=$id)
				DropError("Страница с таким ЧПУ уже существует! В системе не может быть двух одинаковых ЧПУ.<br><a href='javascript:history.back();'>вернуться и исправить</a>");
	
			$weight++; 

			$query = " 	UPDATE pages SET
							weight = weight+1
						WHERE parent_id = $parent_id AND weight>=$weight"; 
			query($query,"u");
	
			$query = " 	UPDATE pages SET
							module_id = '$module_id', 
							parent_id = '$parent_id', 
							weight = '$weight', 
							chpu = '$chpu', 
							access_level='$access_level',
							title_l".$SITE_LANGUAGE_ID." = '$title',
							ptitle_l".$SITE_LANGUAGE_ID." = '$ptitle',
							description_l".$SITE_LANGUAGE_ID." = '$description',
							keywords_l".$SITE_LANGUAGE_ID." = '$keywords',
							content_l".$SITE_LANGUAGE_ID." = '$content1',
							content2_l".$SITE_LANGUAGE_ID." = '$content2'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "pages_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{

			$query = " 	SELECT COUNT(id) as total
						FROM pages
						WHERE parent_id = $id"; 
			$total = query($query,"s");
			$total = $total[0]['total'];
			
			if ($total>0)
				DropError("У данной страницы есть подстраницы. Сперва перенесите их в другую страницу или удалите.<br><a href='javascript:history.back();'>вернуться и исправить</a>");
				
			$query = " 	DELETE FROM pages
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "pages_list.php";
		}
	}

	header("Location: $location");
	exit;

?>