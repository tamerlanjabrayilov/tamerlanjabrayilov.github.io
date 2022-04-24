<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	$title = "Загрузка изображения";
	include ("cms_template/_top.php");
//----------------------------------------------

	$thumb_width	= $global_thumb_width ;
	$thumb_height	= $global_thumb_height;

	$valid_types 		=  array("jpg", "jpeg", "png", "gif");
	
//	$cache_widths 		=  "650,610,249,285,145";
	
	@ $description = $_POST['description'];
	@ $width = $_GET['width'];
	@ $widthPOST = $_POST['width'];
	if ($widthPOST>0)
		$width=$widthPOST;

	@ $type = $_GET['type'];
	@ $id = $_GET['id'];

?>

	<form enctype="multipart/form-data" method="post" name="image_upload" action="images_upload.php?width=<?=$width?>&type=<?=$type?>&id=<?=$id?>"> 
	<table class="tbl">
		<tr class="title">
			<td>Загрузка изображения</td>
		</tr>
		<tr>
			<td>
<?

	if (isset($_FILES["userfile"])) 
	{
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) 
		{
			$filename = $_FILES['userfile']['tmp_name'];
			$ext = substr($_FILES['userfile']['name'], 1 + strrpos($_FILES['userfile']['name'], "."));
			if (filesize($filename) > $max_image_size) 
			{
				echo "<p class='lang' align='center'>Ошибка: Размер файла > ".(int) ($max_image_size/1024)."Kb</p>";
			} 
			elseif (!in_array(strtolower($ext), $valid_types)) 
			{
				echo "<p class='title_r' align='center'>Ошибка: Неверный тип файла.</p>";
			} 
			else 
			{
				$size = GetImageSize($filename);
				if (($size) && ($size[0] <= $max_image_width) && ($size[1] <= $max_image_height)) 
				{
					
					$user_filename = substr($_FILES['userfile']['name'],0,strlen($_FILES['userfile']['name'])-strlen($ext));
					$user_filename = "userfiles/".Translit::UrlTranslit($user_filename, TR_NO_SLASHES);
					$tmp_filename = $user_filename;
					$counter = 0;

					while(file_exists($ROOT_RELATIVE.$tmp_filename.".".$ext))
					{
						$tmp_filename = $user_filename."$counter";
						$counter++;
					}

					$user_filename = $tmp_filename.".".$ext;

					if (move_uploaded_file($filename, $ROOT_RELATIVE.$user_filename )) 
					{
						switch ($type)
						{
							case "banners":
								$query = " 	SELECT image FROM banners
											WHERE id='$id'"; 
								$image = query($query,"s");
								if (@ $image[0][0]!=$no_image_file)
									@ unlink ($ROOT_RELATIVE.$image[0][0]);	
								RemoveCache($image[0][0],$cache_widths);
		
								$filename = "b$id-".substr($user_filename,strlen("userfiles/"));
								@ copy($ROOT_RELATIVE.$user_filename, $ROOT_RELATIVE."userfiles/Image/slides/".$filename);
								@ unlink ($ROOT_RELATIVE.$user_filename);
								$user_filename = "/userfiles/Image/slides/".$filename;
		
								$query = " 	UPDATE banners SET
												image = '$user_filename'
											WHERE id='$id'"; 
								query($query,"u");
								echo "<script language='javascript'>location.href='banners_edit.php?id=$id';</script></div>";
								exit;

							break;
							
							case "cv":
								$query = " 	SELECT id,photo FROM hr_resume
											WHERE id='$id'"; 
								$image = query($query,"s");
								if (@ $image[0]['photo']!=$no_image_file)
									@ unlink ($_SERVER['DOCUMENT_ROOT']."/".$image[0]['photo']);										

								$filename = "photo-".$image[0]['id'].".jpg";
								
								$thumb_filename = CreateThumbnail("", $user_filename, "userfiles/Image/cv", $width, "90", $filename);
								unlink ($_SERVER['DOCUMENT_ROOT']."/".$user_filename);		
								
								$query = " 	UPDATE hr_resume SET
												photo = '$thumb_filename'
											WHERE id='$id'"; 
								query($query,"u");
								echo "<script language='javascript'>location.href='cv_edit.php?id=$id';</script></div>";
								exit;

							break;
							
							case "articles":
								$query = " 	SELECT image FROM articles
											WHERE id='$id'"; 
								$image = query($query,"s");
								if (@ $image[0][0]!=$no_image_file)
									@ unlink ($ROOT_RELATIVE.$image[0][0]);	
//								RemoveCache($image[0][0],$cache_widths);
		
								$thumb_filename = CreateThumbnail("", $user_filename, "userfiles/Image/articles", $width);
								@ unlink ($ROOT_RELATIVE.$user_filename);		
								$user_filename = $thumb_filename;
		
								$query = " 	UPDATE articles SET
												image = '$user_filename'
											WHERE id='$id'"; 
								query($query,"u");
//								echo "<div class='success' align='center'>Файл успешно загружен!<br><br>";
								echo "<script language='javascript'>location.href='articles_edit.php?id=$id';</script></div>";
//								echo "<a class='button' href=\"javascript:location.href='articles_edit.php?id=$id'\">Вернуться к редактированию статьи</a></div>";
								exit;

							break;
							
							
							case "album":
								$thumb_filename = CreateThumbnail("", $user_filename, "userfiles/Image/albums", $width,"95","album-$id.$ext");
								@ unlink ($ROOT_RELATIVE.$user_filename);		
								$user_filename = $thumb_filename;
		
								$query = " 	UPDATE albums SET image='$thumb_filename' WHERE id='$id'"; 
								query($query,"u");
								echo "<script language='javascript'>location.href='albums_edit.php?id=$id';</script></div>";
								exit;
							break;							

							default:
								echo "<p class='error' align='center'>Не указан тип загружаемого изображения!</p>";
								echo "<a class='button' href=\"javascript:history.go(-1);\">Вернуться</a></div>";
							break;

						}
					} 
					else 
					{
						echo "<p class='error' align='center'>Ошибка: Невозможно загрузить файл...</p>";
					}
				} 
				else 
				{
					echo "<p class='error' align='center'>Ошибка: Неверное разрешение файла:<br>
								Максимальная ширина: $max_image_width<br>
								Максимальная высота: $max_image_height</p>";
				}
			}
		} 
		else 
		{
			echo "<p class='error' align='center'>Ошибка: Размеры файла превышает: ".(int) ($max_image_size/1024)."Kb</p>";
		}
	} 
	else 
	{
?>
				<p class="warning">Убедитесь что изображение имеет следующие размеры: ширина &lt;= <b><?=$max_image_width?>px</b>, 
					высота &lt;=<b><?=$max_image_height?>px</b> и размер файла не превышает <b><? echo (int) ($max_image_size/1024); ?>Kb</b>.<br>
					 Для загрузки допустимы файлы с расширениями: 
				<?
				foreach ($valid_types as $f)
				{
					echo "<b>".$f."</b> ";
				}
				?>
				</p>
			</td>
		</tr>
		<?
        if ($type == "item")
        {
            
        ?>
            <tr>
                <td colspan="2">
                
                <br /><br />
				<input type="file" name="file_upload" id="file_upload" />
                
				<script type="text/javascript">
					$(function() {
						$('#file_upload').uploadify({
							'swf'      : '/cms/modules/jquery/uploadify/uploadify.swf',
							'uploader' : '/cms/modules/jquery/uploadify/uploadify.php',
							'fileTypeExts' : '*.gif; *.jpg; *.jpeg; *.png;',
							'width'    : 200,
							'auto' :true,
							'onUploadSuccess'   	 : function(file, data, response) 
							{
								var id = '<?=$id?>';
								var width = '<?=$width?>';
								var filename = file.name;
								$.post("/cms/modules/_dbget.php",{operation:'uploadItemFoto', id:id, filename:filename, width:width},function(data){ if(data!="") {alert ('Ошибка: '+data)} });
							}		
						});
					});                
                </script>
                <br>
                <a class='button' href="item_edit.php?id=<?=$id?>">Вернуться</a>
                    
                </td>
            </tr>
        
        <?
            echo "<input name='width' type='hidden' class='text' value='$width'>";
        }
        elseif ($type == "albums")
        {
            
        ?>
            <tr>
                <td colspan="2">
                
                <br /><br />
				<input type="file" name="file_upload" id="file_upload" />
                
				<script type="text/javascript">
					$(function() {
						$('#file_upload').uploadify({
							'swf'      : '/cms/modules/jquery/uploadify/uploadify.swf',
							'uploader' : '/cms/modules/jquery/uploadify/uploadify.php',
							'fileTypeExts' : '*.gif; *.jpg; *.jpeg; *.png;',
							'width'    : 200,
							'auto' :true,
							'onUploadSuccess'   	 : function(file, data, response) 
							{
								var albumid = '<?=$id?>';
								var width = '<?=$width?>';
								var filename = file.name;
								$.post("/cms/modules/_dbget.php",{operation:'uploadFoto', albumid:albumid, filename:filename, width:width},function(data){ if(data!="") {alert ('Ошибка: '+data)} });
							}		
						});
					});                
                </script>
                <br>
                <a class='button' href="albums_edit.php?id=<?=$id?>">Вернуться</a>
                    
                </td>
            </tr>
        
        <?
            echo "<input name='width' type='hidden' class='text' value='$width'>";
        }
        else
        {
            echo "<input name='width' type='hidden' class='text' value='$width'>";
        ?>
                <tr>
                    <td><br>Выберите файл:<br>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_image_size?>">
                        <input class="text_mandatory" id="mandatory[]"  name="userfile" type="file" style="width:200px; " >
						<br><br>
						<input class="submit" type="submit" value="Загрузить"> 
                        <input type="hidden" name="brand_id" value="<?=$brand_id?>">
                    </td>
                </tr>
        
        <?
        }
	}
?>
 
	</table>
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>
