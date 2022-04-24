<?php
	header('Content-type: text/html; charset=utf-8');
	
	session_start();

	setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus');

//----------------------------------------------------------------------------------------------
//	Common variables initiation
//----------------------------------------------------------------------------------------------

	// ${ROOT}
	$ROOT = "http://pit.az/";
	$CMS_ROOT = $ROOT."cms/";
	$CMS_MODULES = $CMS_ROOT."modules/";
	$CMS_FCKeditor = $CMS_MODULES."fckeditor/";

	$MODULES = array();

	// DB connection parameters

	$config->host = "db02.hostline.ru";
	$config->login = "vh79271_pit";
	$config->pass = "QHrFNCpJ";
	$config->dbname = "vh79271_pit";	
	

	// DB connection parameters
	// remote
	
	// images
 	$global_thumb_width = "300";
	$global_thumb_height = "300";

	$no_image_file = "userfiles/no_image.jpg";

 	$global_image_width = "800";
	$global_image_height = "800";

	$max_image_width	= 8000;
	$max_image_height	= 8000;
	$max_image_size		= 16000 * 1024;


//----------------------------------------------------------------------------------------------
//	CMS Modules include
//----------------------------------------------------------------------------------------------

@	include ("modules/_common.php"); // common module (should be always included )
@	include ("modules/_mysql.php"); // mysql connect module (should be always included )
@	include ("modules/_menu.php");
@	require ("modules/_translit.php");
@	include ("modules/_pages.php");
@	include ("modules/ckeditor/ckeditor.php") ;
@	require_once("modules/phpmailer/class.phpmailer.php");
@	include ("modules/_users.php");
@	include ("modules/_images.php");

@	include ("modules/_banners.php");
//@	include ("modules/_news.php");
//@	include ("modules/_albums.php");
//@	include ("modules/_testimonials.php");
//@	include ("modules/_board.php");
//@	include ("modules/_faqs.php");
//@	include ("modules/_articles.php");
//@	include ("modules/_actions.php");
//@	include ("modules/_subscribe.php");

//@	include ("modules/_shop.php");
@	include ("modules/_hr.php");

	array_unique($MODULES);
//----------------------------------------------------------------------------------------------
//	Language
//----------------------------------------------------------------------------------------------


$DEFAULT_LANGUAGE = "ru";

if (!empty($SITE_LANGUAGE))
{
	@ $SITE_LANGUAGE_ID = GetLanguageByName($SITE_LANGUAGE);
	if (empty($SITE_LANGUAGE_ID))
	{
		if (empty($_SESSION['l']))
		{
			$url_ind = 0; // this mean that there is no language parameter in url
			@ $SITE_LANGUAGE_ID = GetLanguageByName($DEFAULT_LANGUAGE);
			@ $SITE_LANGUAGE = $DEFAULT_LANGUAGE;
			$_SESSION['l'] = $SITE_LANGUAGE;
			$_SESSION['lid'] = $SITE_LANGUAGE_ID;
		}
		else
		{
			$SITE_LANGUAGE = $_SESSION['l'];
			@ $SITE_LANGUAGE_ID = GetLanguageByName($SITE_LANGUAGE);
			$url_ind = 0; // this mean that there is no language parameter in url
			$_SESSION['lid'] = $SITE_LANGUAGE_ID;
		}
	}
	else
	{
		$_SESSION['l'] = $SITE_LANGUAGE;
		$_SESSION['lid'] = $SITE_LANGUAGE_ID;
		$url_ind = 1; // there is a language parameter in url
	}
	
}
elseif (!empty($_POST['l']))
{
	@ $SITE_LANGUAGE_ID = GetLanguageByName($_POST['l']);
	@ $SITE_LANGUAGE = $_POST['l'];
	$_SESSION['l'] = $SITE_LANGUAGE;
	$_SESSION['lid'] = $SITE_LANGUAGE_ID;
}
else
{
	if (empty($_SESSION['l']))
	{
		$url_ind = 0; // this mean that there is no language parameter in url
		@ $SITE_LANGUAGE_ID = GetLanguageByName($DEFAULT_LANGUAGE);
		@ $SITE_LANGUAGE = $DEFAULT_LANGUAGE;
		$_SESSION['l'] = $SITE_LANGUAGE;
		$_SESSION['lid'] = $SITE_LANGUAGE_ID;
	}
	else
	{
		$SITE_LANGUAGE = $_SESSION['l'];
		@ $SITE_LANGUAGE_ID = GetLanguageByName($SITE_LANGUAGE);
		$_SESSION['lid'] = $SITE_LANGUAGE_ID;
	}
}

// select text variables for current language
$TEXT_VARS = GetTextVars();


// relative path to root
//$slashes = explode ("/", $_SERVER['REQUEST_URI']);
//$ROOT_RELATIVE = "";
//for ($i=2; $i<sizeof($slashes); $i++)
//	$ROOT_RELATIVE .= "../";

$ROOT_RELATIVE = $_SERVER['DOCUMENT_ROOT']."/";

$IMAGES = $ROOT_RELATIVE."images/";

srand((double)microtime()*1000000); 

?>
