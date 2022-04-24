<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("users");

	if (@ $_SESSION['LoggedUser']['level']<50)
		DropError("Доступ закрыт! Недостаточно прав!<br><a href='$CMS_ROOT'>вернуться в начало</a>");

	$title = "Пользователи";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
	<table class="tbl">
	<tr class="title">
		<td width="33%">Имя</td>
		<td width="33%">Email</td>
		<td width="33%">Группа</td>
	</tr>
	<? 	
		$users = GetUser("*");
		$c = 1;
		foreach ($users as $user)
		{
			if ($c<0)
				$bg = "#eeeeee";
			else
				$bg = "#fafafa";
				
			if ($_SESSION['LoggedUser']['level']>=$user['access_level']) 
				echo "<tr bgcolor='$bg'><td><a href='users_edit.php?id=".$user['id']."'>".$user['name']."</a></td><td>".$user['email']."</td><td>".$user['group_name']."</td></tr>";
			else
				echo "<tr bgcolor='$bg'><td>".$user['name']."</td><td>".$user['email']."</td><td>".$user['group_name']."</td></tr>";

			$c = -$c;
		}
	?>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>