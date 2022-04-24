<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Фотогалерея</a> :: Редактирование категории";
	include ("cms_template/_top.php");
//----------------------------------------------
?>

<?

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
@ 	$delete= $_GET['delete'];
	if ($delete=="1")
	{
		$id = $_GET['id'];
		$query = " 	DELETE FROM albums_category
					WHERE id = '$id'"; 
		query($query,"d");
		echo "<script>location.href='albums_list.php';</script>";
	}


@ 	$name = $_POST['name'];
	$name = NoQuotes($name);
	
@ 	$weight = $_POST['weight'];
	
	
	if ($id=="new")
	{
		$query = " 	INSERT INTO albums_category
						(name, weight)
					VALUES
						('$name', '$weight')"; 
		query($query,"i");
		echo "<script>location.href='albums_list.php';</script>";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE albums_category SET
							name='$name',
							weight='$weight'
						WHERE id = '$id'"; 
			query($query,"u");
			echo "<script>location.href='albums_list.php';</script>";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM albums_category
						WHERE album_id = '$id'"; 
			query($query,"d");
			echo "<script>location.href='albums_list.php';</script>";
		}
	}


 	$id = $_GET['id'];
	if ($id=="new")
	{
	}
	else
	{
		$query = " 	SELECT 
						*
					FROM 
						albums_category
					WHERE id='$id'"; 
		$album = query($query,"s");		
		$name = $album[0]['name'];
		$weight = $album[0]['weight'];
	}
?>
	<form name="album_category_edit" method="POST" action="albums_category_edit.php">
		<div class="eitem">
			<div class="eitem1">
				Название категории:
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="name" value="<?=$name?>">
			</div>
		</div>
		<div class="eitem">
			<div class="eitem1">
				Порядок (больше - ниже):
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="weight" value="<?=$weight?>">
			</div>
		</div>
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">
	</form>
    


<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>    