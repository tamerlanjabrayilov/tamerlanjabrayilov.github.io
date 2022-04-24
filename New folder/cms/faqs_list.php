<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("faqs");

	$title = "Вопросы и Ответы";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
	<table class="tbl">
	<tr class="title">
		<td width="100%">Вопрос</td>
	</tr>
<?
 	
	$faqs = GetFAQs();
	$c = 1;
	foreach ($faqs as $faq)
	{
		if ($c<0)
			$bg = "#eeeeee";
		else
			$bg = "#fafafa";
			
		if ($faq['approved']=='1')
			$out = "<tr bgcolor='$bg'><td><a href='faqs_edit.php?id=".$faq['id']."'>".substr($faq['question_l'.$SITE_LANGUAGE_ID],0,100)."</a></td></tr>";
		else
			$out = "<tr bgcolor='#ffcccc'><td><a href='faqs_edit.php?id=".$faq['id']."'>".substr($faq['question_l'.$SITE_LANGUAGE_ID],0,100)."</a></td></tr>";
		echo $out;
		$c = -$c;		
	}
?>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>