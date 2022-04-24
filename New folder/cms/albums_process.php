<?
	include ("_configs.php");
	include ("modules/_authorization.php");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
@ 	$name = $_POST['name'];
	$name = NoQuotes($name);
@ 	$date_created = $_POST['date_created'];
@ 	$category_id = $_POST['category_id'];
@ 	$description = $_POST['description'];
@ 	$h_title = $_POST['h_title'];
@ 	$h_desc = $_POST['h_desc'];
@ 	$image = $_POST['image'];
	if (trim($image)=="")
		$image = $no_image_file;
	
	if ($id=="new")
	{
		$query = " 	INSERT INTO albums
						(name,description,date_created,category_id,h_title,h_desc,image)
					VALUES
						('$name','$description',NOW(),'$category_id','$h_title','$h_desc','$image')"; 
		$insert_id = query($query,"i");
		
		$album_folder = "userfiles/Image/albums/$insert_id";
		if (!is_dir($ROOT_RELATIVE.$album_folder))
			mkdir($ROOT_RELATIVE.$album_folder, 0777, true);
		
		$location = "albums_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE albums SET
							name='$name', 
							description='$description', 
							category_id='$category_id', 
							h_title='$h_title', 
							h_desc='$h_desc', 
							image='$image', 
							date_created = '$date_created'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "albums_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	SELECT 
							im.imagefile
						FROM 
							images im
						LEFT JOIN images_albums imal ON imal.image_id = im.id
						WHERE 
							imal.album_id = '$id'"; 
			$imagefiles = query($query,"s");
	
			$query = " 	SELECT 
							image
						FROM 
							albums
						WHERE 
							id = '$id'"; 
			$image = query($query,"s");
	
			$query = " 	DELETE FROM im USING images im, images_albums imal
						WHERE 
							imal.image_id = im.id AND imal.album_id = '$id'"; 
			query($query,"d");
			
			foreach ($imagefiles as $imagefile)
			{
				@ unlink ($ROOT_RELATIVE.$imagefile['imagefile']);
			}
			if ($image[0]['image']!=$no_image_file)
				@ unlink ($ROOT_RELATIVE.$image[0]['image']);

			$query = " 	DELETE FROM albums
						WHERE id = '$id'"; 
			query($query,"d");

			$query = " 	DELETE FROM images_albums
						WHERE album_id = '$id'"; 
			query($query,"d");
			$location = "albums_list.php";
		}
	}
	header("Location: $location");
	exit;

?>