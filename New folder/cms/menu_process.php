<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
@ 	$menu_name = NoQuotes($_POST['menu_name']);
@ 	$menu_set_id = $_POST['menu_set_id'];
@ 	$href = $_POST['href'];
@ 	$menu_image = $_POST['menu_image'];

	if ($id=="new")
	{
		$query = " 	SELECT
						COUNT(weight) as total_weight
					FROM 
						menu
					WHERE menu_set_id='$menu_set_id' AND parent_id='0' ";
		$count = query($query,"s");
		$count = $count[0]['total_weight'];

		$languages = SelectLanguage();
		$query_fields = "";
		$query_values = "";
		foreach ($languages as $lang)
		{
			$query_fields .= ",menu_name_l".$lang['ind'];
			$query_values .= ",'$menu_name'";
		}
		$query = " 	INSERT INTO menu
						(parent_id,href,weight,menu_set_id $query_fields)
					VALUES
						('0','$href','$count','$menu_set_id' $query_values)"; 
		query($query,"i");
		$location = "menu_list.php?menu_set_id=$menu_set_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE menu SET
							menu_name_l${SITE_LANGUAGE_ID} = '$menu_name', 
							href = '$href' 
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "menu_list.php?menu_set_id=$menu_set_id";
		}
		elseif ($operation=="delete")
		{
			$mc = SelectMenuChilds($id,$menu_set_id);
			if (!empty($mc))
			{
				DropAlert("У этого пункта меню есть подпункты! Сперва удалите их или перенесите.");
			}
			else
			{
				$query = " 	SELECT
								COUNT(weight) as total_weight
							FROM 
								menu
							WHERE menu_set_id='$menu_set_id' AND parent_id='$parent_id' ";
				$count = query($query,"s");
				$count = $count[0]['total_weight'];
						
				$query = " 	SELECT
								parent_id,
								weight
							FROM 
								menu
							WHERE id='$id'";
				$old_parent = query($query,"s");
				$old_parent_id = $old_parent[0]['parent_id'];
				$old_weight = $old_parent[0]['weight'];
				
				$query = " 	DELETE FROM menu
							WHERE id = '$id'"; 
				query($query,"d");
			
				$query = " 	UPDATE menu SET
								weight=weight-1
							WHERE menu_set_id='$menu_set_id' AND parent_id='$old_parent_id' AND weight>'$old_weight'"; 
				query($query,"u");					
			}

			$location = "menu_list.php?menu_set_id=$menu_set_id";
		}
	}
	header("Location: $location");
	exit;
?>