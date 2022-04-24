<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Фотогалерея</a> :: Редактирование альбома";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="album_edit" method="POST" action="albums_process.php">
<?

 	$id = $_GET['id'];
	if ($id=="new")
	{
		$date_created = date("Y-m-d G:i:s");
	}
	else
	{
		$album = SelectAlbum($id);
		
		$name = $album[0]['name'];
		$description = $album[0]['description'];
		$category_id = $album[0]['category_id'];
		$date_created = (!empty($album[0]['date_created'])?date("Y-m-d G:i:s",$album[0]['date_created']):date("Y-m-d G:i:s"));
		$h_desc = $album[0]['h_desc'];
		$h_title = $album[0]['h_title'];
		$image = $album[0]['image'];
	}
?>
		<div class="eitem">
			<div class="eitem1">
				Название альбома:
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="name" value="<?=$name?>">
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
		        Категория:
			</div>
			<div class="eitem2">
				<select name="category_id">
				<?
					$query ="SELECT * FROM albums_category ORDER BY weight";
					$categories = query($query,"s");
					foreach ($categories as $category)
					{
						echo "<option value='".$category['id']."' ".($category['id']==$category_id?"selected":"");
						echo " >".$category['name']."</option>";
					}
				?>
				</select>
			</div>
		</div>	
    
		<div class="eitem">
			<div class="eitem1">
		        Дата публикации:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]"  type="text" name="date_created" value="<?=$date_created?>"><br>
				<a class="button" onClick="document.album_edit.date_created.value='<?=date("Y-m-d G:i:s")?>';">Сбросить на сегодня</a>
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
		        Картинка:
			</div>
			<div class="eitem2">
				<?
				if ($id!="new")
				{
					if (empty($image))
						$image = $no_image_file;
					echo PutThumbnail($image, 200, 150); 
				?>
					<input type="hidden" name="image" value="<?=$image?>">
					<br>
					<a class='delete' onClick="document.album_edit.image.value='<?=$no_image_file?>';document.album_edit.submit();">Убрать изображение</a>
					<a class='button' href='images_upload.php?width=<?=$global_thumb_width?>&type=album&id=<?=$id?>'>Загрузить новое изображение</a>
				<?
				}
				else
					echo "Сохраните, чтобы загружать картинку!";
				?>
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
				Заголовок (title):
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="h_title" value="<?=$h_title?>">
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
				Описание (description):
			</div>
			<div class="eitem2">
				<input id="mandatory[]" class="text_mandatory" type="text" name="h_desc" value="<?=$h_desc?>">
			</div>
		</div>

		<div class="eitem">
			Описание:<br>
			<?	PutFCKeditor('description', $description);	?>
		</div>
		
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">
	</form>
	
	<br>
	<br>
	<br>
	<br>

	<form name="album_content" method="post" action="albums_content_process.php">
	<table class="tbl">
		<tr class="title">
			<td>Фотографии данного проекта</td>
		</tr>
		<tr>
			<td>
<?
		$photos = GetAlbumContent($id);
		$count = 1;
		echo "<table border='0' align='center' width='100%'><tr>";
		foreach ($photos as $photo)
		{
			if ($count%5==0)
			{
				echo "</tr><tr>";
				$count = 1;
			}
				
			echo "<td style='text-align:center;' width='25%' align='center'><a href='$ROOT".$photo['imagefile']."' target='_blank'>";
			echo PutThumbnail($photo['imagefile'],200,150,$photo['description'."_l$SITE_LANGUAGE_ID"],"85","","1");
			echo "</a><br><div><input type='checkbox' id='i".$photo['id']."' name='images[]' value='".$photo['id']."'>&nbsp;<input type='text' name='desc[".$photo['id']."]' value='".$photo['description'."_l$SITE_LANGUAGE_ID"]."' onClick=document.getElementById('i".$photo['id']."').checked='1';></div></td>";
			
			$count++;
		}

		for ($i=$count; $i<=4; $i++)
			echo "<td width='25%'></td>";
		echo "</table>";
			
?>
		</td>
		<tr>
			<td>
				<a class='delete' onClick="document.album_content.operation.value='delete';document.album_content.submit();">Удалить выбранные</a>
				<a class='button' onClick="document.album_content.operation.value='save';document.album_content.submit();">Сохранить выбранные</a>
				&nbsp;&nbsp;&nbsp;
				<a class='button' href='images_upload.php?width=<?=$global_image_width?>&type=albums&id=<?=$id?>'>Загрузить фото</a>
				<input type="hidden" name="id" value="<?=$id?>">
				<input type="hidden" name="operation" value="save">
			</td>
		</tr>
	</table>
	</form>


<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>