<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("faqs");

@ 	$id = $_POST['id'];
@ 	$operation= $_POST['operation'];
	
	
@ 	$author = $_POST['author'];
	$author = NoQuotes($author);
	$author = strip_tags($author);

@ 	$email = $_POST['email'];
	$email = NoQuotes($email);
	$email = strip_tags($email);

@ 	$date_created = $_POST['date_created'];

@ 	$question = $_POST['question'];
	$question = NoQuotes($question);
	$question = strip_tags($question);

@ 	$answer = $_POST['answer'];

@ 	$approved = $_POST['approved'];
	if (@ empty($approved))
		$approved="0";
	else
		$approved="1";

@ 	$user_id = $_POST['user_id'];
	
	if ($id=="new")
	{
		$languages = SelectLanguage();
		$query_fields = "";
		$query_values = "";
		foreach ($languages as $lang)
		{
			$query_fields .= ",question_l".$lang['ind'].",answer_l".$lang['ind'];
			$query_values .= ",'$question','$answer'";
		}

		$query = " 	INSERT INTO faqs
						(author, date_created, email, user_id, approved $query_fields)
					VALUES
						('$author', NOW(), '$email', '$user_id', '$approved' $query_values )"; 
		query($query,"i");
		$location = "faqs_edit.php?id=$insert_id";
	}
	else
	{
		if ($operation=="save")
		{
			$query = " 	UPDATE faqs SET
							author = '$author', 
							date_created = '$date_created', 
							question_l${SITE_LANGUAGE_ID} = '$question',
							answer_l${SITE_LANGUAGE_ID} = '$answer',
							email = '$email', 
							user_id = '$user_id', 
							approved = '$approved'
						WHERE id = '$id'"; 
			query($query,"u");
			$location = "faqs_edit.php?id=$id";
		}
		elseif ($operation=="delete")
		{
			$query = " 	DELETE FROM faqs
						WHERE id = '$id'"; 
			query($query,"d");
			$location = "faqs_list.php";
		}
	}
	header("Location: $location");
	exit;

?>