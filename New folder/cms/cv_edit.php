<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("hr");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Анкеты</a> :: Редактирование анкеты";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="cv_edit" method="post" action="cv_process.php" >

<?
	if ($id=="new")
	{
	}
	else
	{
		$tmp = GetResume($id);
		$resume = $tmp[0];
	}	
?>

		<h2>Анкета №: <?=$resume['id']?></h2>
		<strong>Добавлена: <?=($id!="new"?date("d.m.Y", strtotime($resume['date_created'])):"сегодня")?></strong><br />
	
    	<hr /><br />


		<div class="eitem">
			<div class="eitem1">
		        Дата закрытия:
			</div>
			<div class="eitem2">
				<input style="width:200px" class="text_mandatory" id="mandatory[]"  type="text" name="date_end" value="<?=($id!="new"?date("d.m.Y", strtotime($resume['date_end'])):date("d.m.Y"))?>"><a class="button" onClick="document.cv_edit.date_end.value='<?=date("d.m.Y")?>';">Сбросить на сегодня</a>
			</div>
		</div>
	
		<div class="eitem">
			<div class="eitem1">
		        <h2>Тип анкеты:</h2>
			</div>
			<div class="eitem2">
                <select name="type">
                    <option value="resume" <?=($resume['type']=="resume"?"selected":"")?> >Наши работники</option>
                    <option value="cv" <?=($resume['type']=="cv"?"selected":"")?> >Свободные вакансии</option>
                </select>            
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
		        Должность:
			</div>
			<div class="eitem2">
                <select name="position_id">
                    <?
                        $tmp = GetPosition();
                        foreach ($tmp as $t)
                        {
                            echo "<option value='".$t['id']."' ".($resume['position_id']==$t['id']?"selected":"").">".$t['name']."</option>";	
                        }
                    ?>
                </select>            
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
		        Зарплата:
			</div>
			<div class="eitem2">
				<input style="width:200px"  id="mandatory[]" class="text_mandatory"  type="text" name="zp" value="<?=$resume['zp']?>"> $/мес
			</div>
		</div>	
			
		<div class="eitem">
			<div class="eitem1">
		        Опыт:
			</div>
			<div class="eitem2">
				<input style="width:200px"  class="text"  type="text" name="exp" value="<?=$resume['exp']?>"> лет/года
			</div>
		</div>	
			
		<div class="eitem">
			<div class="eitem1">
		        Город/Страна:
			</div>
			<div class="eitem2">
				<input class="text"  type="text" name="city" value="<?=$resume['city']?>">
			</div>
		</div>	

		<div class="eitem">
			<div class="eitem1">
		        Образование:
			</div>
			<div class="eitem2">
                <select name="edu_id">
                    <?
                        $tmp = GetEducation();
                        foreach ($tmp as $t)
                        {
                            echo "<option value='".$t['id']."' ".($resume['edu_id']==$t['id']?"selected":"").">".$t['name']."</option>";	
                        }
                    ?>
                </select>            
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
		        Фото работника<br />(только для раздела<br />"Наши работники"):
			</div>
			<div class="eitem2">
				<?
				if ($id!="new")
				{
					$image = $resume['photo'];
					if (empty($image))
						$image = $no_image_file;
					echo PutImage($image, $global_thumb_width, 0); 
				?>
					<input type="hidden" name="image" value="<?=$image?>">
					<br>
					<a class='delete' onClick="document.cv_edit.image.value='<?=$no_image_file?>';document.cv_edit.submit();">Убрать изображение</a>
					<a class='button' href='images_upload.php?width=<?=$global_thumb_width?>&type=cv&id=<?=$id?>'>Загрузить новое изображение</a>
				<?
				}
				else
					echo "Сохраните, чтобы загружать картинку!";
				?>
			</div>
		</div>
	
		<div class="eitem">
			<div class="eitem1">
		        Отображать на главной?:
			</div>
			<div class="eitem2">
                <select name="hot">
                    <option value="0" <?=($resume['hot']=="0"?"selected":"")?> >Нет</option>
                    <option value="1" <?=($resume['hot']=="1"?"selected":"")?> >Да</option>
                </select>            
			</div>
		</div>
		
		
		<br><br>	
		<div class="eitem">
			<strong>Подробное описание вакансии</strong> (для Соискателей) или <strong>Отзыв о работнике</strong> (для Работодателей):<br />
			<? PutFCKeditor('description', $resume['description']);	?>	
		</div>
		
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="operation" value="save">
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>