<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("pages");

	if (@$_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Страницы</a> :: Редактирование страницы";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="pages_edit" method="post" action="pages_process.php" onSubmit="return CheckMandatory(this);" >
<?

	if ($id=="new")
	{
		$p_module_id = "2";
		$page['parent_id']=0;
	}
	else
	{
		$page = SelectPage($id);
		$page = $page[0];
		$p_module_id = $page['module_id'];
	}
?>
			<div class="eitem">
				<div class="eitem1">
					Тип страницы:
				</div>
				<div class="eitem2">
					<? 
					ShowModules($page['id'],$p_module_id);
					?>
				</div>
			</div>
			
			<script language="javascript">
				var pages_array = new Array();
			<?
				$pages = SelectPage();
				array_push($pages, array("id"=>0));
				foreach ($pages as $p)
				{
					$SubPages = SelectPageChilds($p['id']);
					$pagesArray = "<option value='0' >[Сделать первой]</option>";
					if (@ !empty($SubPages))
					{
						foreach ($SubPages as $sp)
						{
							if ($sp['id']!=$id)
							{
								$pagesArray .= "<option value='".$sp['weight']."'";
								if ($sp['weight']<$page['weight'] && $page['parent_id']==$sp['parent_id'])
								{
									$pagesArray .= " selected ";
								}
								$pagesArray .= " >".substr(str_replace("\"","'",strip_tags($sp['title'."_l$SITE_LANGUAGE_ID"])),0,75)."</option>";
							}
						}
					}
					echo " pages_array[".$p['id']."] = \"$pagesArray\";  \n\n";
				}
			?>
			</script>

			<div class="eitem">
				<div class="eitem1">
					Страница находится в разделе:
				</div>
				<div class="eitem2">
					<select name="parent_id" class="text" style='width:240px;' onChange="document.getElementById('subpages').innerHTML='<select name=&quot;weight&quot; class=&quot;text&quot;  style=&quot;width:240px;&quot;>'+pages_array[this.value]+'</select>'"> 
					<option value='0'>[ROOT]</option>
						<? ShowPages(0,0,"select",$page['id'],$page['parent_id']); ?>
					</select>
					
					&nbsp; &nbsp; После: 
					<span id="subpages">
						<select name="weight" class="text" style='width:240px;'>
							<script language="javascript"> document.write(pages_array[<?=$page['parent_id']?>]); </script>
						</select>
					</span>
				</div>
			</div>
			
			
			<div class="eitem">
				<div class="eitem1">
					Заголовок:
				</div>
				<div class="eitem2">
					<input class="text_mandatory" id="mandatory[]"  type="text" name="title" value="<?=$page['title'."_l".$SITE_LANGUAGE_ID]?>" onBlur="ChangeCHPU(this);">
				</div>
			</div>

			<div class="eitem">
				<div class="eitem1">
					ЧПУ:
				</div>
				<div class="eitem2">
					<input id="mandatory[]" class="text_mandatory"  type="text" name="chpu" value="<?=$page['chpu']?>">
				</div>
			</div>
			
			<div class="eitem">
				<div class="eitem1">
					Заголовок (&lt;title&gt;&lt;/title&gt;):
				</div>
				<div class="eitem2">
					<input class="text_mandatory" id="mandatory[]"  type="text" name="ptitle" value="<?=$page['ptitle'."_l".$SITE_LANGUAGE_ID]?>" onBlur="ChangeCHPU(this);">
				</div>
			</div>
					
			<div class="eitem">
				<div class="eitem1">
					Описание (&lt;description&gt;&lt;/description&gt;):
				</div>
				<div class="eitem2">
					<input class="text"  type="text" name="description" value="<?=$page['description'."_l".$SITE_LANGUAGE_ID]?>">
				</div>
			</div>
			
			<div class="eitem">
				<div class="eitem1">
					Ключевые слова (&lt;keywords&gt;&lt;/keywords&gt;):
				</div>
				<div class="eitem2">
					<input class="text"  type="text" name="keywords" value="<?=$page['keywords'."_l".$SITE_LANGUAGE_ID]?>">
				</div>
			</div>

			<div class="eitem">
				<div class="eitem1">
					Уровень доступа:
				</div>
				<div class="eitem2">
					<select name="access_level">
					<option value="0">ВСЕ ГРУППЫ</option>
					<? 
						$groups = GetGroup();
						$section="";
					
						foreach ($groups as $group)
						{
							$section .= "<option value=".$group['access_level']." ";
							if ($group['access_level']==$page['access_level'])
								$section .= " selected ";
							$section .=">".$group['name']."</option>";
						}
						echo $section;
					?>
					</select>				
				</div>
			</div>
			

			<div class="eitem">
            	Основной текст страницы:<br><br>
				
				<? 
					PutFCKeditor('content', $page['content'."_l".$SITE_LANGUAGE_ID]);	
				?>	
			</div>
				
			<div class="eitem">
				Дополнительный текст страницы:<br><br>

				<? 
					PutFCKeditor('content2', $page['content2'."_l".$SITE_LANGUAGE_ID]);	
				?>	
			</div>
			
			<input type="hidden" name="id" value="<?=$id?>">
			<input type="hidden" name="operation" value="save">
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>