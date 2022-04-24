<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("hr");

	@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
	
@ 	$position_id = NoQuotes($_POST['position_id']);
@ 	$date_end = date("Y-m-d G:i:s", strtotime($_POST['date_end']));
@ 	$zp = NoQuotes($_POST['zp']);
@ 	$type = NoQuotes($_POST['type']);
@ 	$exp = NoQuotes($_POST['exp']);
@ 	$city = NoQuotes($_POST['city']);
@ 	$edu_id = NoQuotes($_POST['edu_id']);
@ 	$description = NoQuotes($_POST['description']);
@ 	$hot = NoQuotes($_POST['hot']);

@ 	$photo = $_POST['photo'];
	if (trim($photo)=="")
		$photo = $no_image_file;
	
	if ($id=="new")
	{
		$languages = SelectLanguage();
		$query_fields = "";
		$query_values = "";
		
		foreach ($languages as $lang)
		{
			$query_fields .= ",city_l".$lang['ind'].", description_l".$lang['ind'];
			$query_values .= ",'$city', '$description'";
		}
		$query = " 	INSERT INTO hr_resume
						(`date_created`, `date_end`, `photo`, `position_id`, `zp`, `type`, `exp`, `edu_id`, `hot` $query_fields )
					VALUES
						(NOW(), '$date_end', '$photo', '$position_id', '$zp', '$type', '$exp', '$edu_id', '$hot' $query_values)"; 
		$insert_id = query($query,"i");
		$location = "cv_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			if (!empty($existPageId) && $existPageId!=$id)
				DropError("Статья с таким ЧПУ уже существует! В системе не может быть двух одинаковых ЧПУ.<br><a href='javascript:history.back();'>вернуться и исправить</a>");

			$query = " 	UPDATE hr_resume SET
							date_end = '$date_end',
							position_id = '$position_id', 
							zp = '$zp', 
							type = '$type',
							exp = '$exp',
							edu_id = '$edu_id',
							hot = '$hot',
							city_l".$_SESSION['lid']."='$city',
							description_l".$_SESSION['lid']."='$description'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "cv_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM hr_resume
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "cv_list.php";
		}
	}
	header("Location: $location");
	exit;

?>