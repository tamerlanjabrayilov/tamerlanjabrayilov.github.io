<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("pages");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Константы</a> :: Редактирование константы";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="textvars_edit" method="post" action="textvars_process.php" onSubmit="return CheckMandatory(this)" >
<?

 	$id = $_GET['id'];
	if ($id=="new")
	{
?>
		<div class="eitem">
			<div class="eitem1">
				Название переменной:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]" type="text" name="name" value=""><br>
				<div align="left">Используйте ТОЛЬКО латинские буквы для имени переменной! <strong>Не используйте название "new"</strong></div>
			</div>
		</div>
	<?
	}
	else
	{
	?>
		<div class="eitem">
			<div class="eitem1">
				Название переменной:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]" readonly="yes" type="text" name="name" value="<?=$id?>"><br>
				<div align="left">Вы можете только изменять значение у существующей переменной!</div>
			</div>
		</div>
	<?
	}

	$query = " 	SELECT *
				FROM 
					textvars 
				WHERE name='$id'"; 
	$textvar = query($query,"s");

	$query = " 	SELECT *
				FROM 
					languages 
				ORDER BY ind "; 
	$tmp = query($query,"s");
	
	foreach ($tmp as $t)
	{
		?>
        <div class="eitem">
            <div class="eitem1">
                <h1><?=$t['name']?></h1>
            </div>
            <div class="eitem2">
                <textarea name="value_l<?=$t['ind']?>"><?=$textvar[0]['value_l'.$t['ind']]?></textarea>
            </div>
        </div>
        <?
	}	
	?>

	<div class="eitem">
		<div class="eitem1">
			Описание переменной:
		</div>
		<div class="eitem2">
			<input class="text" type="text" name="description" value="<?=$TEXT_VARS[$id."_d_"]?>">
		</div>
	</div>
	
	<input type="hidden" name="operation" value="save">
	<input type="hidden" name="id" value="<?=$id?>">
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>