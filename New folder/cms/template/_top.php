<?
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	
	$main_menu_set_id = 1;
	@ $menu = SelectMenuForPage($PageContent['chpu'],$main_menu_set_id);
	@ $menu_id = $menu[0]['id'];
	@ $parent_menu_id = $menu[0]['parent_id'];

	 $captcha_code = "/cms/modules/captcha.php?width=80";
?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<head>
<title><?=@ (empty($unit_title)?$PageContent['ptitle'."_l$SITE_LANGUAGE_ID"]:$unit_title)?></title><meta name="Description" content="<?=@ (empty($unit_desc)?$PageContent['description'."_l$SITE_LANGUAGE_ID"]:$unit_desc)?>"><meta name="Keywords" content="<?=@ (empty($PageContent['keywords'."_l$SITE_LANGUAGE_ID"])?$unit_title:$PageContent['keywords'."_l$SITE_LANGUAGE_ID"])?>">
<meta content=general name=rating><meta name="Document-state" content="Dynamic"><meta name="revisit-after" content="3 days"><meta name="robots" content="index,follow"><meta name="copyright" content="Дизайн студия Инфоарт : infoart.net.ua">
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />

<link rel="stylesheet" type="text/css" href="/cms/template/_style.css?v=30" />

<?
	if ($_SESSION['l']=="az")
	{
//		echo '<link rel="stylesheet" type="text/css" href="/cms/template/_style_az.css" />';	
	}
	
?>

<!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="/cms/template/ie6_style.css">
<![endif]-->
<?=$TEXT_VARS['counter_GA']?>
</head>

<body class="body">
<?=$TEXT_VARS['counter_YM']?>

	<div id="wrapper">
    
        <div id="top">
        	<div class="content">
                <a href="/<?=$_SESSION['l']?>/" id="logo"><img src="/images/logo.png" alt=""></a>
                <div id="topMenu">
                    <?
                        $top_menu = SelectMenuChilds(0,1);
                        foreach ($top_menu as $m)
                        {
                            echo "<a href='/".$_SESSION['l']."/".$m['href']."/' ".($m['href']==$PageContent['chpu']?" class='active'":"").">".$m['menu_name_l'.$_SESSION['lid']]."</a>";	
                            
                        }
                    ?>
                </div>
                <div id="topLang"><a href="/ru/" <?=($_SESSION['l']=="ru"?"class='active'":"")?>>RU</a><a href="/en/" <?=($_SESSION['l']=="en"?"class='active'":"")?>>EN</a><a href="/az/" <?=($_SESSION['l']=="az"?"class='active'":"")?>>AZ</a></div><div id="topLangWrapper"><?=$_SESSION['l']?></div>
            </div>
		</div>
        <?
        if ($template_name!="index")
		{
		?>
            <div id="lineMenu">
                <div class="content">
                    <div class="menuTop">
                        <div class='wrp'>
                    <?
                    
                        $menuArray = array();
                        $menuArray = ShowPath($PageContent['chpu'],$menuArray);
                        
                        if (sizeof($menuArray)>1)
                        {
                            echo "<a href='".$menuArray[0]['url']."'>".$menuArray[0]['title']."</a>";
                            echo "<div class='mi'>&larr; ";
                            for ($i=1;$i<sizeof($menuArray);$i++)
                            {
                                if ($i>1)
                                    echo "/";
                                if (!empty($menuArray[$i]['url']))
                                    echo " <a href='".$menuArray[$i]['url']."' >".$menuArray[$i]['title']."</a> ";
                                else
                                    echo " <span>".$menuArray[$i]['title']."</span> ";
                            }
                            echo "</div>";
                        }
                        else
                            echo "<span >".$menuArray[0]['title']."</span>";
                        
                    ?>
                        </div>
                    </div>
                    <div class="buttonsTop">
                        <?=$TEXT_VARS['top_phones']?>
                    </div>
                </div>
            </div>
            <div id="lineGray"></div>
		<?
		}
		?>        
            