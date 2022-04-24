<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Баннеры</a> :: Редактирование баннера";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="banners_edit" method="post" action="banners_process.php" >
<?

	@ $title = $_POST['title'];
	@ $brand_id = $_POST['brand_id'];
	@ $model_chpu = $_POST['model_chpu'];

	@ $url = $_POST['url'];
	@ $code = $_POST['code'];

//	@ $width = $_POST['width'];
//	@ $height = $_POST['height'];
	$width = 950;
	$height = 420;

	
	@ $image = $_POST['image'];
	@ $description = $_POST['description'];

	@ $id = $_POST['id'];
	if (empty($id))
	 	$id = $_GET['id'];
	
	if ($id=="new")
	{
	}
	else
	{
		$banners = GetBanners($id);
		$banner = $banners[0];
	
		if (empty($title))
			$title = $banner['title'."_l$SITE_LANGUAGE_ID"];
/*		if (empty($brand_id))
			$brand_id = $banner['brand_id'];
		if (empty($model_chpu))
			$model_chpu = str_replace(" ","",strtolower($banner['model_chpu']));
*/
		if (empty($width))
			$width = $banner['width'];
		if (empty($height))
			$height = $banner['height'];
		if (empty($url))
			$url = $banner['url'];
		if (empty($image))
			$image = $banner['image'];
		if (empty($code))
			$code = $banner['code'];
		if (empty($description))
			$description = $banner['description'];
			
		$regions = array();
		$regions = explode(",",$banner['regions']);
	
	}
?>
	<!--
		<div class="eitem">
			<div class="eitem1">
				Заголовок баннера:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]"  type="text" name="title" value="<?=$title?>">
			</div>
		</div>		
	-->
		<div class="eitem">
			<div class="eitem1">
				Идентификатор:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]"  class="text" type="text" name="code" value="<?=$code?>">
                <br /><em>(порядок определяется по этому УНИКАЛЬНОМУ имени. ТОЛЬКО ЛАТИНИЦА!)</em>
                
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
		        Статус:
			</div>
			<div class="eitem2">
            	<select name="status">
                	<option <?=($banner['status']=="1"?" selected ":"")?> value="1">Показывать</option>
                	<option <?=($banner['status']=="0"?" selected ":"")?> value="0">НЕ показывать</option>
                </select>
			</div>
		</div>	

		<div class="eitem">
			<div class="eitem1">
				URL:
			</div>
			<div class="eitem2">
				<input class="text_mandatory" id="mandatory[]"  class="text" type="text" name="url" value="<?=$url?>">
			</div>
		</div>		
		
		<div class="eitem">
			<div class="eitem1">
				Ширина:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="width" value="<?=$width?>" readonly="readonly"> px
			</div>
		</div>		
	
		<div class="eitem">
			<div class="eitem1">
				Высота:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="height" value="<?=$height?>" readonly="readonly"> px
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
					echo PutImage($image, $width, $height); 
				?>
					<input type="hidden" name="image" value="<?=$image?>">
					<br>
					<a class='delete' onClick="document.banners_edit.image.value='<?=$no_image_file?>';document.banners_edit.submit();">Убрать изображение</a>
					<a class='button' href='images_upload.php?width=<?=$global_thumb_width?>&type=banners&id=<?=$id?>'>Загрузить новое изображение</a>
				<?
				}
				else
					echo "Сохраните, чтобы загрузить картинку!";
				?>
			</div>
		</div>

		<div class="eitem">
			<div class="eitem1">
				Описание баннера:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="description" value="<?=$description?>">
			</div>
		</div>		
	
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">

	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>