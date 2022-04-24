<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Система управления сайтом iCMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link rel="stylesheet" type="text/css" href="/cms/cms_template/_style.css" />

<link href="/cms/modules/jquery/css/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/cms/modules/jquery/jquery.js"></script>
<script type="text/javascript" src="/cms/modules/jquery/swfobject.js"></script>
<script type="text/javascript" src="/cms/modules/jquery/uploadify/jquery.uploadify.min.js"></script><link rel="stylesheet" type="text/css" href="/cms/modules/jquery/uploadify/uploadify.css" />

<script type="text/javascript" src="/cms/modules/ckeditor/ckeditor.js"></script>
<script language="javascript">
<!--

$(document).ready(
	function()
	{ 
		
		PosElements();
		
		$(window).scroll(function() 
		{
			$("#tmenu").css('top',$(window).scrollTop()-1+'px');
		});
		
		$(".activeTH td").click(function()
		{
			var thisTD = $(this);
			var thisForm = $(this).parent().parent().parent().attr("rel");
			var thisOrder = $(this).attr("rel");
			var thisOrderDir = $("#orderdir").val();
			if (thisOrderDir=="desc")
				thisOrderDir = "asc";
			else
				thisOrderDir = "desc";
			if (thisOrder=="")
				thisOrderDir = "date_created";
				
			$("#order").val(thisOrder);
			$("#orderdir").val(thisOrderDir);
				
//			$(".activeTH td").removeClass("asc").removeClass("desc");
//			thisTD.addClass(thisOrderDir);
			
			$("#"+thisForm).submit();
		});
	}
);



$(window).resize(
 	function()
	{ 
		PosElements();
	}
);




function PosElements()
{
	var left = $(window).width()-$("#tmenu").width()-40;
	$("#tmenu").css({'margin-left':left+'px','top':'-1px'})
}



function ChangeCHPU(obj)
{
	return true;
}

function CheckMandatory(obj)
{
	for(i=0; i<obj.elements.length; i++)
	{
		value = obj.elements[i].value
		value = value.replace(/ /g,'');

		
		if (value=="" && obj.elements[i].id=="mandatory[]")
		{
			alert("Убедитесь, что все обязательные поля заполнены верно! ");
			return false;
		}
	}
	return true;
}

function ConfirmDelete(obj)
{
	if (confirm("Уверены, что хотите удалить? Операцию нельзя будет отменить.")) 
	{
		obj.operation.value='delete';
		obj.submit();
		return true;
	}
	else
		return false;

}

function ConfirmLanguageSwitch(obj,doConfirm)
{
	if (doConfirm==1)
	{
		if (confirm("Уверены, что хотите переключить язык? Все несохраненные для текущего языка изменения будут потеряны!")) 
			obj.submit();
		else
			obj.reset();
	}
	else
		obj.submit();
}

function CheckUsedModule(obj, oldvalue)
{
	var used_modules_ids;
	templates = new Array();

	<?
		$used_modules_ids="";
		$used_modules = SelectModule();
		foreach ($used_modules as $module)
		{
			@ $assigned = GetAssignedPage($module['id']);
			if (@ !empty($assigned[0]['id']) && $module['multiple']==0 )
				$used_modules_ids .= ",".$module['id'];

			if ($module['template']=="page")
				$template = "";
			else
				$template = $module['template'];
			echo "templates[".$module['id']."]='".$template."'; ";
		}
		echo "used_modules_ids = '$used_modules_ids';";
	?>
	if (used_modules_ids.indexOf(','+obj.value+',')>=0)
	{
		alert ("Этот модуль может быть подключен только к одной странице! Он уже используется.");
		obj.value=oldvalue;
	}
	else
	{
		obj.form.chpu.value=templates[obj.value];
		return true;		
	}
}

function OrderBy(orderType, formName)
{
	document.forms[formName].order.value=orderType;
	document.forms[formName].submit();
}
-->
</script>
</head>

<body>
<div id="main">
	<div id="menu">
		<div class="top">
			<a id="logo" href="/cms/"><img src='/cms/images/logo.png'></a>
		</div>
		<? 
			if (!empty($_SESSION['LoggedUser']['id']))
			{
			?>
			<div id="lmenu">
			<? 
				if (!empty($_SESSION['LoggedUser']['id']) && @ in_array("cms", $_SESSION['LoggedUser']['modules']))
					include ("_menu.php"); 
			?>
			</div>
			<?
			}
		?>
	</div>
	<div id="content">
		<div class="top">
			<div id="title">
				<br>
				<? 
				 	@ $id = $_GET['id'];
				 	@ $menu_set_id = $_GET['menu_set_id'];					
					
					$menu = "<div id='tmenu'>";
					
					if (($_SERVER['SCRIPT_NAME']=="/cms/categorys_edit.php" && $_GET['page']=="photos" && !empty($_GET['id'])) || $_SERVER['SCRIPT_NAME']=="/cms/images_upload.php" || $_SERVER['SCRIPT_NAME']=="/cms/menu_list.php")
					{
					}
					elseif (!empty($id))
						$menu .= "<a onClick='document.forms[1].submit();' class='tsave'>Сохранить</a>";
					
					if (($_SERVER['SCRIPT_NAME']=="/cms/categorys_edit.php" && $_GET['page']=="photos" && !empty($_GET['id'])) || $_SERVER['SCRIPT_NAME']=="/cms/images_upload.php" || $_SERVER['SCRIPT_NAME']=="/cms/menu_list.php")
					{}
					elseif ($id!="new" && !empty($id))
						$menu .= "<a onClick='return ConfirmDelete(document.forms[1]);' class='tdel'>Удалить</a>";
						
					if (($id=="new" || $_SERVER['SCRIPT_NAME']=="/cms/index.php" || $_SERVER['REQUEST_URI']=="/cms/menu_list.php") || $_SERVER['SCRIPT_NAME']=="/cms/images_upload.php")
					{}
					elseif ($_SERVER['SCRIPT_NAME']=="/cms/categorys_edit.php" && $_GET['page']=="photos" && !empty($_GET['id']))
					{
						$menu .= "<a href='/cms/item_edit.php?category_id=$id&id=new'>Добавить</a>";
					}
					elseif ($_SERVER['SCRIPT_NAME']=="/cms/item_edit.php")
					{
						$menu .= "<a href='/cms/item_edit.php?category_id=$category_id&id=new'>Добавить</a>";
					}
					else
						$menu .= "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_edit.php?id=new".(!empty($menu_set_id)?"&menu_set_id=$menu_set_id":"")."'>Добавить</a>";
						
					$menu .= "</div>";
					echo "$menu";
				
					if (!empty($_SESSION['LoggedUser']['id']))
						PutLanguageForm(0);
				?>
				<h1><?=$title?></h1>	
			</div>
		</div>
		<div id="text">
	