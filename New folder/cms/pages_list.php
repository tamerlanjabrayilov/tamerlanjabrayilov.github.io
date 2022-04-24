<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

	if (@ $_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "Страницы";
	include ("cms_template/_top.php");
//----------------------------------------------

	ShowPages(0,0,"cms",0,0);
	
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>