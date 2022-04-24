<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("users");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
@ 	$group_id = $_POST['group_id'];

@ 	$date_registered=$_POST['date_registered'];
@ 	$name=$_POST['name'];
	$name = NoQuotes($name);
@ 	$email=$_POST['email'];
	$email = NoQuotes($email);
@ 	$password=$_POST['password'];
	$password = NoQuotes($password);
@ 	$approved=$_POST['approved'];

	if ($id=="new")
	{
		$query = " 	INSERT INTO users 
						(group_id,date_registered,name,email,password,approved)
					VALUES 
						('$group_id','$date_registered','$name','$email','$password','$approved')";
		query($query,"i");
		$location = "users_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE users SET
						group_id = '$group_id',
						date_registered = '$date_registered',
						name = '$name',
						email = '$email',
						password = '$password',
						approved='$approved'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "users_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM users
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "users_list.php";
		}
	}

	header("Location: $location");
	exit;

?>