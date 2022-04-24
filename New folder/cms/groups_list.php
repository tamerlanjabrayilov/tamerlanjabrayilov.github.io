<?
	include ("_configs.php");
	include ("modules/_authorization.php");
	
	if (@$_SESSION['LoggedUser']['level']!=100)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "Группы";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
	<table class="tbl">
	<tr class="title">
		<td width="33%">Группа</td>
		<td width="33%">Уровень</td>
		<td width="34%">Модули</td>
	</tr>
	<? 	
		$groups = GetGroup();
		$c = 1;
		foreach ($groups as $group)
		{
			if ($c<0)
				$bg = "#eeeeee";
			else
				$bg = "#fafafa";
				
			echo "<tr bgcolor='$bg'><td><a href='groups_edit.php?id=".$group['id']."'>".$group['name']."</a></td><td>".$group['access_level']."</td><td>".$group['available_modules']."</td></tr>";

			$c = -$c;
		}
	?>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>