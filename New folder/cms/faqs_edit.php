<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	CheckModuleAccess("faqs");

	$title = "<a href='".substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],"_"))."_list.php'>Вопросы и Ответы</a> :: Редактирование ответа";
	include ("cms_template/_top.php");
//----------------------------------------------
?>
	<form name="faqs_edit" method="post" action="faqs_process.php" >
<?

 	$id = $_GET['id'];
	if ($id=="new")
	{
	}
	else
	{
		$faqs = GetFAQs($id);
		$faq=$faqs[0];
	}
	
?>

		<div class="eitem">
			<div class="eitem1">
		        Автор отзыва:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="author" value="<?=$faq['author']?>" onBlur="ChangeCHPU(this);">
			</div>
		</div>
		
		<div class="eitem">
			<div class="eitem1">
		        Контакт:
			</div>
			<div class="eitem2">
				<input class="text"  type="text" name="email" value="<?=$faq['email']?>">
			</div>
		</div>	
			
		<div class="eitem">
			<div class="eitem1">
		        Дата публикации:
			</div>
			<div class="eitem2">
				<input class="text" type="text" name="date_created" value="<?=date("Y-m-d G:i:s", $faq['date_created'])?>"><br>
				<a class="button" onClick="document.faqs_edit.date_created.value='<?=date("Y-m-d G:i:s")?>';">Сбросить на сегодня</a>
			</div>
		</div>
        
			
		<div class="eitem">
			<div class="eitem1">
		        Опубликован?
			</div>
			<div class="eitem2">
				<input type="checkbox" name="approved" 
			<?
			if ($faq['approved']=='1')
				echo " checked";
			?>
			>   
			</div>
		</div>
     

		<div class="eitem">
			Вопрос:<br><br>
			<?	PutFCKeditor('question', $faq['question_l1']);	?>
		</div>
        
        
   		<div class="eitem">
			Ответ:<br><br>
			<? PutFCKeditor('answer', $faq['answer'."_l1"]);	?>	
		</div>

        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="operation" value="save">
	</form>
<?
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>
