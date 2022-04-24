<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("users");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Пользователи</a> :: Редактирование пользователя";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="users_edit" method="post" action="users_process.php" onSubmit="return CheckMandatory(this);" >
<?

 	$id = $_GET['id'];
	if ($id=="new")
	{
	}
	else
	{
		$users = GetUser($id);
		$user = $users[0];
		if (@$_SESSION['LoggedUser']['level']<$user['access_level'])
			DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");
	}
?>

		<div class="eitem">
			<div class="eitem1">
				Группа:
			</div>
			<div class="eitem2">
				<? 
				if ($_SESSION['LoggedUser']['level']>=50)
				{
					$groups = GetGroup();
					$section="<select name='group_id'>";
					foreach ($groups as $group)
					{
						$section .= "<option value=".$group['id']." ";
						if ($group['id']==$user['group_id'])
							$section .= " selected ";
						$section .=">".$group['name']."</option>";
					}
					echo $section."</select>";
				}
				else
				{
					$group = GetGroup($user['group_id']);
					echo "<strong><input type='hidden' value='".$group[0]['id']."' name='group_id'>".$group[0]['name']."</strong>";
				}
				?>
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Имя пользователя:
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="name" value="<?=$user['name']?>">
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Логин (email):
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="email" value="<?=$user['email']?>">
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Пароль:
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="password" value="<?=$user['password']?>">
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Дата регистрации:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="date_registered" value="<?=date("Y-m-d G:i:s", $user['date_registered'])?>"><br><a class="button" onClick="document.users_edit.date_registered.value='<?=date("Y-m-d G:i:s")?>';">Сбросить на сегодня</a>
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Статус:
			</div>
			<div class="eitem2">
				<select name="approved">
					<option value="1" <?=($user['approved']=="1"?" selected ":"")?>>Активен</option>
					<option value="0" <?=($user['approved']=="0"?" selected ":"")?>>Не активен</option>
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