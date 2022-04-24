<?
	include ("_configs.php");
	include ("modules/_authorization.php");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
@ 	$title = $_POST['title'];
	$title = NoQuotes($title);

@	$brand_id = $_POST['brand_id'];
@	$model_chpu = $_POST['model_chpu'];

@ 	$url = $_POST['url'];
@ 	$code = $_POST['code'];

@ 	$width = $_POST['width'];
@ 	$height = $_POST['height'];
@ 	$description = $_POST['description'];

@ 	$status = $_POST['status'];

@ 	$image = $_POST['image'];
	if (trim($image)=="")
		$image = $no_image_file;
	
	if ($id=="new")
	{
		$languages = SelectLanguage();
		$query_fields = "";
		$query_values = "";
		foreach ($languages as $lang)
		{
			$query_fields .= ",title_l".$lang['ind'];
			$query_values .= ",'$title'";
		}
		$query = " 	INSERT INTO banners
						(url, width, height, image, code, description, status $query_fields )
					VALUES
						('$url', '$width', '$height', '$image', '$code', '$description', '$status' $query_values)"; 
		query($query,"i");
		$location = "banners_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE banners SET
							title_l${SITE_LANGUAGE_ID} = '$title', 
							url = '$url', 
							width = '$width', 
							height = '$height', 
							image = '$image',
							code = '$code',
							description = '$description',
							status = '$status'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "banners_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM banners
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "banners_list.php";
		}
	}
	header("Location: $location");
	exit;

?>