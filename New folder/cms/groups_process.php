<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("users");

	if (@$_SESSION['LoggedUser']['level']!=100)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
@ 	$group_id = $_POST['group_id'];

@ 	$name=$_POST['name'];
	$name = NoQuotes($name);
@ 	$access_level=$_POST['access_level'];

	foreach ($MODULES as $m)
	{
		if (!empty($_POST[$m]))
			$available_modules .="$m,";
	}
	$available_modules = substr($available_modules,0,-1);
	
	if ($id=="new")
	{
		$query = " 	INSERT INTO groups 
						(name,access_level,available_modules)
					VALUES 
						('$name','$access_level','$available_modules')";
		query($query,"i");
		$location = "groups_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE groups SET
						name = '$name',
						access_level = '$access_level',
						available_modules='$available_modules'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "groups_list.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM groups
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "groups_list.php";
		}
	}

	header("Location: $location");
	exit;

?>