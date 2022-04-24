<?php
@ session_start();
header('Content-Type: text/html; charset=utf8');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') // check if ajax request
{
	include '../_configs.php';
	
	$operation = $_POST["operation"];


	switch ($operation)
	{

		case "sendForm":
			@ $name = mysql_real_escape_string($_POST["name"]);
			@ $email = mysql_real_escape_string($_POST["email"]);
			@ $phone = mysql_real_escape_string($_POST["phone"]);
			@ $company = mysql_real_escape_string($_POST["company"]);
			@ $theme = mysql_real_escape_string($_POST["theme"]);
			
			@ $question = mysql_real_escape_string(strip_tags(preg_replace("%\n%", "", nl2br($_POST["question"])),"<br>"));

			@ $f_cv = mysql_real_escape_string($_POST["f_cv"]);
			@ $f_uploadedFile = mysql_real_escape_string($_POST["f_uploadedFile"]);
			
			@ $form = (mysql_real_escape_string($_POST["form"]));
		
			if(($_SESSION['security_code']!=$_POST['security_code'] || empty($_SESSION['security_code'])) && !empty($_POST['security_code']))	
			{
				echo $TEXT_VARS['captcha_error'];
			}
			else
			{
				if ($form=="sendCV")
				{
					// send email with question
					$message = "<h2>Заявка на сайте: CV</h2> 
					<br><br><strong>Имя:</strong> $name 
					<br><br><strong>Телефон:</strong> $phone
					<br><br><strong>E-Mail:</strong> <a href='mailto:$email'>$email</a>
					<br><br><strong>Должность:</strong> $f_cv 
					<br><br><strong>Отзыв:</strong> <br>$question
					<br><br>________________________________________________";
		
		
					$mailer = new extPHPMailer();
					$mailer->Subject = "Заявка на сайте: CV";
					$mailer->Body = $message;
					$mailer->AddAddress($TEXT_VARS['contact_email']);
					$mailer->From = $TEXT_VARS['contact_email2'];

					$file = pathinfo($f_uploadedFile);
					$imageName = $file['basename'];
					$imageExt = $file['extension'];

					$mailer->AddAttachment($_SERVER['DOCUMENT_ROOT']."/cms/modules/jquery/fileupload/server/php/files/$f_uploadedFile", Translit::UrlTranslit($f_cv."-".$imageName, TR_NO_SLASHES));
					
				}				
				elseif ($form=="sendOrder")
				{
					// send email with question
					$message = "<h2>Заявка на сайте PIT</h2> 
					<br><br><strong>Имя:</strong> $name 
					<br><br><strong>Телефон:</strong> $phone
					<br><br><strong>Организация:</strong> $company
					<br><br><strong>E-Mail:</strong> <a href='mailto:$email'>$email</a>
					<br><br><strong>Тема обращения:</strong> $theme
					<br>
					<br><br><strong>Дополнительная информация:</strong> <br>$question
					<br><br>________________________________________________";
		
		
					$mailer = new extPHPMailer();
					$mailer->Subject = "Заявка на сайте PIT";
					$mailer->Body = $message;
					$mailer->AddAddress($TEXT_VARS['contact_email']);
					$mailer->From = $TEXT_VARS['contact_email2'];
					
					$file = pathinfo($f_uploadedFile);
					$imageName = $file['basename'];
					$imageExt = $file['extension'];

					$mailer->AddAttachment($_SERVER['DOCUMENT_ROOT']."/cms/modules/jquery/fileupload/server/php/files/$f_uploadedFile", Translit::UrlTranslit($f_cv."-".$imageName, TR_NO_SLASHES));
				}
				elseif($form=="contacts")
				{
							
					$message = "<h2>".$TEXT_VARS['feedback_message_title'].":</h2> 
					<br><br>________________________________________________ 
					<br><br><strong>Имя:</strong> $name 
					<br><strong>Телефон:</strong> $phone 
					<br><strong>Email:</strong> $email <a href='mailto:$email'>написать</a>
					<br><br><strong>Вопрос:</strong> <br>$question
					<br><br>________________________________________________";
					
					
					$mailer = new extPHPMailer();
					$mailer->Subject = $TEXT_VARS['feedback_message_title'];
					$mailer->Body = $message;
					$mailer->AddAddress($TEXT_VARS['contact_email']);
					$mailer->From = $TEXT_VARS['contact_email2'];
				}
					
				$mailer->AddBCC("olegtishkov@gmail.com");
					
				if(!$mailer->Send())
				{
					echo "Возникла ошибка при отправке письма: ".$mailer->ErrorInfo;
				}
				$mailer->ClearAddresses();
			}
		break;		
			




		case "uploadItemFoto":
			@ $id = $_POST["id"];
			@ $user_filename = $_POST["filename"];
			@ $width = $_POST["width"];
			
			$item = GetItems($id);
			
			$imageMain = $item[0]['filename'];
			$imageTmbName = substr(md5(time()),0,5)."-".$item[0]['name'].".jpg";
			$category_folder = "userfiles/Image/shop/".$item[0]['category_chpu'];
			if (!is_dir($ROOT_RELATIVE.$category_folder))
				mkdir($ROOT_RELATIVE.$category_folder, 0755);	
			$thumb_filename = CreateThumbnail("", "/userfiles/".$user_filename, $category_folder, $width,"90",$imageTmbName);
			unlink ($_SERVER['DOCUMENT_ROOT']."/userfiles/".$user_filename);		
	
			$query = " 	INSERT INTO item_photo 
							(item_id,filename,description,isMain)
						VALUES 
							('$id','$thumb_filename','','".(!empty($imageMain)?"0":"1")."')"; 
			query($query,"i");
		break;
		
		case "uploadFoto":
			@ $id = $_POST["albumid"];
			@ $user_filename = "userfiles/".$_POST["filename"];
			@ $width = $_POST["width"];
			$album = SelectAlbum($id);
			
			$imageTmbName = substr(md5(time()),0,5)."-".$album[0]['name'].".jpg";
			$album_folder = "userfiles/Image/albums/".$album[0]['id'];
			
			if (!is_dir($ROOT_RELATIVE.$album_folder))
				mkdir($ROOT_RELATIVE.$album_folder, 0777);	
			$thumb_filename = CreateThumbnail("", $user_filename, $album_folder, $width,"90",$imageTmbName);
			unlink ($ROOT_RELATIVE.$user_filename);		
			$user_filename = $thumb_filename;
	
	
			$query = " 	INSERT INTO images 
							(imagefile,description_l".$SITE_LANGUAGE_ID.",date_created)
						VALUES 
							('$user_filename','$description',NOW())"; 
			query($query,"i");
											
			$query = " 	INSERT INTO images_albums
							(image_id,album_id)
						VALUES 
							('$insert_id','$id')"; 
			query($query,"i");
		
		break;		
	
	
	}
}
?>