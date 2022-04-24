<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("pages");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php?menu_set_id=".$_GET['menu_set_id']."'>Меню</a> :: Редактирование пункта меню";

	include ("cms_template/_top.php");
//----------------------------------------------

	function PutPagesTree($id,$level,$current)
	{
		global $SITE_LANGUAGE_ID;
		
		$pages = SelectPageChilds($id);
		$out = "";
		foreach ($pages as $p)
		{
			echo "titles['".$p['chpu']."']=\"".str_replace("\"","'",$p['title_l'.$SITE_LANGUAGE_ID])."\";";
			echo "urls['".$p['chpu']."']=\"".$p['chpu']."\";\n";

			$dashes = "";
			for ($i=0;$i<=$level;$i++)
				$dashes .= "&ndash;";
			$out .= "<option value='".$p['chpu']."'";
			if ($p['id']==$current)
				$out .= " selected ";
			$out .= ">".$dashes.substr(str_replace("\"","'",strip_tags($p['title'."_l".$SITE_LANGUAGE_ID])),0,75)."</option>";
			
			$level++;
			$out .= PutPagesTree($p['id'],$level,$current);
			$level--;
		}
		return $out;
	}
?>
	<form name="menu_edit" method="post" action="menu_process.php" onSubmit="return CheckMandatory(this)" >
<?

 	$id = $_GET['id'];
	$menu_set_id = $_GET['menu_set_id'];
	if ($id=="new")
	{
	}
	else
	{
		$query = " 	SELECT 
						*
					FROM 
						menu
					WHERE id='$id'"; 
		$section = query($query,"s");
		$section = $section[0];
		
		$href = $section['href'];

		@ $page = SelectPageCHPU($href);
		@ $page_id = $page[0]['id'];
	}
?>

		<div class="eitem">
			<div class="eitem1">
				Выберите страницу сайта для этого пункта:
			</div>
			<div class="eitem2">
				<script language="javascript">
					titles = new Array();
					urls = new Array();
					function UpdateUrl(value)
					{
						if (value=='tourism' || value=='treatment' || value=='pilgrimage')
							document.menu_edit.href.value='tours/'+urls[value]+'';
						else
							document.menu_edit.href.value=''+urls[value]+'';
	
						document.menu_edit.menu_name.value=titles[value];
					}
				<?
					$PagesTree = PutPagesTree(0,0,$page_id);
				?>
				</script>
				<select name="href_page" class="text" onChange="UpdateUrl(this.value);">
				<option value='0' selected>[Не выбрана]</option>
				<? echo $PagesTree; ?>
				</select>
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
				Или задайте адрес ссылки вручную:
			</div>
			<div class="eitem2">
				<input class="text"  type="text" name="href" value="<?=$section['href']?>" ><div class="warning">(данное значение имеет более высокий приоритет, чем выбранная страница)</div>
			</div>
		</div>


		<div class="eitem">
			<div class="eitem1">
				Название пункта меню:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]" type="text" name="menu_name" value="<?=$section['menu_name'."_l$SITE_LANGUAGE_ID"]?>">
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
				Находится в меню:
			</div>
			<div class="eitem2">
				<select name="menu_set_id" class="text">
				<? 
					$MenuSet = GetMenuSet($section['menu_set_id']);
					foreach ($MenuSet as $m)
					{
						echo "<option value='".$m['id']."' ".($m['id']==$menu_set_id?"selected":"")." >".$m['description']."</option>";	
					}
				?>
				</select>
			</div>
		</div>
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">
		
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>