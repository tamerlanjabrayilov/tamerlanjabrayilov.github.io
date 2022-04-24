<?php 

	$_URL = explode('/', $_SERVER['QUERY_STRING']);

	// recognize language
	@ $SITE_LANGUAGE = $_URL[0];
	$url_ind = 1;

	// configs include
 	include ("cms/_configs.php");

	if ($url_ind == 0)
	{
		$URL = $_SERVER['QUERY_STRING'];
//		header('HTTP/1.1 301 Moved Permanently');
		header("Location: ".$ROOT.$_SESSION['l']."/".$URL);
	}

	$module = $_URL[$url_ind];
	
	switch ($module)
	{

		case "employers":
			$template_name = $module;
			@ $worker_id = $_URL[$url_ind+1];
			
			if (!empty($worker_id))
				$worker = GetResume($worker_id);
				
			if (!empty($worker))
			{
				$worker = $worker[0];
				$unit_title = $worker['position_name'].", ".$worker['edu_name'].", ".$worker['city'].". ";
				$unit_desc = $worker['position_name'].", ".$worker['edu_name'].", ".$worker['city'].". ";
			}
			else
				$worker_id=$worker=0;
				
			$PageContent = SelectPageCHPU($module);
			@ $PageContent = $PageContent[0];
		break;
		
		case "workers":
			$template_name = $module;
			@ $worker_id = $_URL[$url_ind+1];
			
			if (!empty($worker_id))
				$worker = GetResume($worker_id);
				
			if (!empty($worker))
			{
				$worker = $worker[0];
				$unit_title = $worker['position_name']." ".$worker['zp']."$, ".$worker['city'].". ";
				$unit_desc = $worker['position_name']." ".$worker['zp']."$, ".$worker['city'].". ";
			}
			else
				$worker_id=$worker=0;
				
			
			$PageContent = SelectPageCHPU($module);
			@ $PageContent = $PageContent[0];
		break;
		

		case "articles":
			$template_name = $module;
			@ $article_name = $_URL[$url_ind+1];
			if (!empty($article_name))
			{
				@ $Article = GetArticlesCHPU($article_name);
				$unit_title = $Article[0]['title_l1'].". ";
				$unit_desc = $Article[0]['title_l1'].". ";
			}
			
			$PageContent = SelectPageCHPU($module);
			@ $PageContent = $PageContent[0];
		break;
		
	
		case "contacts":
			$template_name = $module;
			$PageContent = SelectPageCHPU($module);
			$PageContent = $PageContent[0];
		break;
	
		case "index":
			$template_name = "index";
			$page_id = GetIndexPage();
			$PageContent = SelectPage($page_id);		
			$PageContent = $PageContent[0];
		break;
	
		default:
			@ $PageContent = SelectPageCHPU($module);
			@ $PageContent = $PageContent[0];
			$template_name = $module;
			if (@ empty($PageContent['id']) && !empty($template_name))
			{
				$template_name = "page404"; // show 404 content
				$page_id = GetIndexPage();
				$PageContent = SelectPageCHPU($template_name);
				$PageContent = $PageContent[0];
			}
			elseif (@ empty($template_name))
			{
				$template_name = "index";
				$page_id = GetIndexPage();
				$PageContent = SelectPageCHPU($template_name);
				$PageContent = $PageContent[0];
			}
			else
			{
				$template_name = "page";
				$PageContent = SelectPageCHPU($module);
				$PageContent = $PageContent[0];
			}
		break;
	}

	include ("cms/template/_top.php");
	include ("cms/template/$template_name.tpl.php");
	include ("cms/template/_bottom.php");
?>


