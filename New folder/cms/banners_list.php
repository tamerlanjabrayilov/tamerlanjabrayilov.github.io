<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	$title = "Баннеры";
	include ("cms_template/_top.php");
//----------------------------------------------

?>
	<table class="tbl">
	<tr class="title">
		<td width="15%">ID</td>
		<td width="60%">Изображение</td>
		<td width="25%">Описание</td>
	</tr>
<?
 	
	$banners = GetBanners();
	$c = 1;
	foreach ($banners as $banner)
	{
		if ($banner['status']==0)
			$bg = "#ffeeee";
		elseif ($c<0)
			$bg = "#eeeeee";
		else
			$bg = "#fafafa";

		$out = "<tr bgcolor='$bg'><td><a href='banners_edit.php?id=".$banner['id']."'><h3>".$banner['code']."</h3></a></td><td>".PutBanner($banner['code'],'image')."</td><td>".$banner['description']."</td></tr>";
		echo $out;
		$c = -$c;		
	}
?>
	<tr>
		<td colspan="3" align="right"><a href="banners_edit.php?id=new" class="button">Создать баннер</a></td>
	</tr>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>