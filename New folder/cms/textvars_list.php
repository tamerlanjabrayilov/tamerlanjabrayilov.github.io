<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("pages");

	$title = "Текстовые константы";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
	<table class="tbl">
	<tr class="title">
		<td width="20%">Имя переменной</td>
		<td width="40%">Значение</td>
		<td width="40%">Описание</td>
	</tr>
<?

	$c = 1;
	$out = "";
	foreach (array_keys($TEXT_VARS) as $key)
	{ 
		if ($c<0)
			$bg = "#eeeeee";
		else
			$bg = "#fafafa";
			
		if (substr($key,-3)!="_d_")	
			$out .= "<tr bgcolor='$bg'><td><a href='textvars_edit.php?id=$key'>".$key."</a></td><td>".$TEXT_VARS[$key]."</td>";
		else
		{
			$out .= "<td>".$TEXT_VARS[$key]."</td></tr>";
			$c = -$c;		
		}
			
	}
	echo $out;
?>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>