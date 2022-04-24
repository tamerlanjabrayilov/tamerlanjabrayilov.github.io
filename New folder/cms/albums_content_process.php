<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	@ $id = $_POST['id'];
	@ $operation = $_POST['operation'];
	@ $images = $_POST['images'];
	@ $desc = $_POST['desc'];
	if (!isset($images))
		DropError("Не выбрано ни одного изображения для обработки!");
		
	if ($operation=="delete")
	{
		foreach ($images as $image_id)
		{
			$query = " 	SELECT 
							imagefile
						FROM 
							images
						WHERE 
							id = '$image_id'"; 
			$imagefiles = query($query,"s");
	
			$query = " 	DELETE FROM images WHERE id = '$image_id'"; 
			query($query,"d");						
	
			$query = " 	DELETE FROM images_albums WHERE image_id = '$image_id'"; 
			query($query,"d");						
	
			foreach ($imagefiles as $imagefile)
			{
				@ unlink ($ROOT_RELATIVE.$imagefile['imagefile']);
			}
		}
	}
	elseif ($operation=="save")
	{
		foreach ($images as $image_id)
		{
			$query = " 	UPDATE images SET description_l${SITE_LANGUAGE_ID}='".$desc[$image_id]."' WHERE id = '$image_id'"; 
			query($query,"u");						
		}
	}
	
	header("Location: albums_edit.php?id=$id");
	exit;
?>