<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

@ 	$operation= $_POST['operation'];
@ 	$id= $_POST['id'];
	
	
@ 	$name = trim($_POST['name']);
	$name = NoQuotes($name);
@ 	$description = trim($_POST['description']);
	$description = NoQuotes($description);
	
	
	$languages = SelectLanguage();	
	foreach ($languages as $lang)
	{
	@ 	$value[$lang['ind']] = mysql_real_escape_string(stripslashes($_POST['value_l'.$lang['ind']]));
	}	

	if ($name=="new")
		DropError("Переменную с таким именем создавать нельзя! Это сервисная переменная. Вернитесь, и введите другое имя");

	if ($id=="new")
	{
		foreach (array_keys($TEXT_VARS) as $key)
		{ 
			if ($key==$name)
				DropError("Переменная с таким именем уже существует! Вернитесь, и введите другое имя");
		}


		$query_fields = "";
		$query_values = "";
		foreach ($languages as $lang)
		{
			$query_fields .= ",value_l".$lang['ind'];
			$query_values .= ",'".$value[$lang['ind']]."'";
		}
		$query = " 	INSERT INTO textvars
						(name, description $query_fields)
					VALUES
						('$name', '$description' $query_values)"; 
		query($query,"i");
		$location = "textvars_edit.php?id=$name";
	}
	else
	{
		if ($operation=="save")
		{
			$query_fields="";
			foreach ($languages as $lang)
			{
				$query_fields .= " value_l".$lang['ind']."='".$value[$lang['ind']]."',";
			}
			
			$query = " 	UPDATE textvars SET
							$query_fields
							description='$description'
						WHERE name = '$name'"; 
			query($query,"u");
			$location = "textvars_edit.php?id=$name";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM textvars
						WHERE name = '$name'"; 
			query($query,"d");
			$location = "textvars_list.php";
		}
	}
	header("Location: $location");
	exit;

?>