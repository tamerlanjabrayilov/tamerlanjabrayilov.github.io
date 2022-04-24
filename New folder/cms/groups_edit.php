<?
	include ("_configs.php");
	include ("modules/_authorization.php");
		
	CheckModuleAccess("users");

	if (@$_SESSION['LoggedUser']['level']!=100)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Группы</a> :: Редактирование группы";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="groups_edit" method="post" action="groups_process.php" onSubmit="return CheckMandatory(this);" >
<?

 	$id = $_GET['id'];
	if ($id=="new")
	{
	}
	else
	{
		$groups = GetGroup($id);
		$group = $groups[0];
	}
?>

		<div class="eitem">
			<div class="eitem1">
				Название группы:
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="name" value="<?=$group['name']?>">
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Уровень доступа:
			</div>
			<div class="eitem2">
				<select name="access_level">
					<option value="0" <?=($group['access_level']==0?" selected ":"")?>>Низкий (нет прав)</option>
					<option value="50" <?=($group['access_level']==50?" selected ":"")?>>Средний</option>
					<option value="100" <?=($group['access_level']==100?" selected ":"")?>>Наивысший (доступно всё)</option>
				</select>
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Доступ к модулям:
			</div>
			<div class="eitem2">
				<?
					$available_modules = split(",",$group['available_modules'].",");
					foreach ($MODULES as $m)
					{
						echo "$m: <input type='checkbox' name='$m' ".(@in_array($m,$available_modules)?" checked ":"")."><br>";
					}
				?>
			</div>
		</div>		
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>