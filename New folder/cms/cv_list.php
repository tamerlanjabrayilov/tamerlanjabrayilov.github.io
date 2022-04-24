<?
	include ("_configs.php");
	include ("modules/_authorization.php");

	CheckModuleAccess("hr");

	$title = "Резюме";
	include ("cms_template/_top.php");
//----------------------------------------------


	@ $position_id = mysql_real_escape_string($_GET['position_id']);
	@ $type = mysql_real_escape_string($_GET['type']);
	
	@ $order = mysql_real_escape_string($_GET['order']);
	if (empty($order))
		$order = "date_created";
	@ $orderdir = mysql_real_escape_string($_GET['orderdir']);
	if (empty($orderdir))
		$orderdir = "desc";
	
?>
	<form method="get" name="cvList" id="cvList" onchange="this.submit()">
    	<label>Тип анкеты</label>
    	<select name="type">
        	<option value="">Все</option>
        	<option value="resume" <?=($type=="resume"?"selected":"")?> >Наши работники</option>
        	<option value="cv" <?=($type=="cv"?"selected":"")?> >Свободные вакансии</option>
        </select>
    
    	&nbsp;&nbsp;&nbsp;&nbsp;
        
    	<label>Должность</label>
    	<select name="position_id">
        	<option value="">Все</option>
            <?
				$tmp = GetPosition();
				foreach ($tmp as $t)
				{
					echo "<option value='".$t['id']."' ".($position_id==$t['id']?"selected":"").">".$t['name']."</option>";	
				}
			?>
        </select>
        
        <input type="hidden" id="order" name="order" value="<?=$order?>"/>
        <input type="hidden" id="orderdir" name="orderdir" value="<?=$orderdir?>"/>
        
    </form>
    <br />

	<table class="tbl" rel="cvList">
	<tr class="title activeTH">
		<td width="5%" rel="id" <?=($order=="id"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >ID</td>
		<td width="5%" rel="type" <?=($order=="type"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Тип</td>
		<td width="10%" rel="position_id" <?=($order=="position_id"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Должность</td>
		<td width="10%" rel="date_created" <?=($order=="date_created"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Добавлено</td>
		<td width="10%" rel="date_end" <?=($order=="date_end"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Окончание</td>
		<td width="10%" rel="zp" <?=($order=="zp"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >ЗП, $</td>
		<td width="10%" rel="exp" <?=($order=="exp"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Опыт, лет</td>
		<td width="20%" rel="edu_id" <?=($order=="edu_id"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Образование</td>
		<td width="20%" rel="city" <?=($order=="city"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >Город/Страна</td>
		<td width="5%" rel="hot" <?=($order=="hot"?($orderdir=="asc"?"class='asc'":"class='desc'"):"")?> >На главной?</td>
	</tr>
<?
 	
	$tmp = GetResume(0,$type,$position_id,0,0,$order,$orderdir);
	foreach ($tmp as $t)
	{
		Echo "	<tr>
					<td>".$t['id']."</td>
					<td>".($t['type']=="cv"?"Вакансия":"Анкета")."</td>
					<td><a href='cv_edit.php?id=".$t['id']."'>".$t['position_name']."</a></td>
					<td>".date("d.m.Y", strtotime($t['date_created']))."</td>
					<td>".date("d.m.Y", strtotime($t['date_end']))."</td>
					<td>".$t['zp']."</td>
					<td>".$t['exp']."</td>
					<td>".$t['edu_name']."</td>
					<td>".$t['city']."</td>
					<td>".($t['hot']==1?"+":"")."</td>
				</tr>";
	}
?>
	</table>
<? 
//----------------------------------------------
	include ("cms_template/_bottom.php");
?>