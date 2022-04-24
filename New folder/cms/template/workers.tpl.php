<?
	if (!empty($menu_id))
	{
		$s_menu = SelectMenuChilds($menu_id,$main_menu_set_id);
		
		if (!empty($s_menu))
		{
			echo '<div id="mright">';
			
			if (!empty($PageContent['content2_l'.$SITE_LANGUAGE_ID]))
				echo $PageContent['content2_l'.$SITE_LANGUAGE_ID]."<br class='br'>";

			foreach ($s_menu as $s)
			{
				if ($s['href']==$PageContent['chpu'])
					echo "<a class='mi active'>".$s['menu_name_l'.$SITE_LANGUAGE_ID]."</a>";
				elseif ($s['href']=="")
					echo "<div>&nbsp;</div>";
				else
					echo "<a href='/".$_SESSION['l']."/".$s['href']."/' class='mi'>".$s['menu_name_l'.$SITE_LANGUAGE_ID]."</a>";
					
					
				$ss_menu = SelectMenuChilds($s['id'],$main_menu_set_id);
				if (!empty($ss_menu))
				{
					echo '<div id="submenu">';
					foreach ($ss_menu as $ss)
					{
						if ($ss['href']==$PageContent['chpu'])
							echo "<a class='active smi'>".$ss['menu_name_l'.$SITE_LANGUAGE_ID]."</a>";
						elseif ($ss['href']=="")
							echo "<div>&nbsp;</div>";
						else
							echo "<a href='/".$_SESSION['l']."/".$ss['href']."/' class='smi'>".$ss['menu_name_l'.$SITE_LANGUAGE_ID]."</a>";
					}
					echo '</div>';
				}
			}
			
			echo '</div>';
		}
		
	}


	if (!empty($worker))
	{
		?>
        <h1>Вакансия &laquo;<?=$worker['position_name']?>&raquo;</h1>
		<h2 class="zpTitle">Зарплата: <?=$worker['zp']?>$/<?=$TEXT_VARS['txt_Months']?></h2>

        <br /><br />
        <h3>Требования:</h3>
        <table class='worker'>
            <tr>
        		<th>Опыт работы</th>    
        		<th>Образование</th>    
        		<th>Местоположение</th>    
        		<th>Дата закрытия вакансии</th>    
		    </tr>
            <tr>
        		<td><?=$worker['exp']?> <?=$TEXT_VARS['txt_Years']?></td>    
        		<td><?=$worker['edu_name']?></td>    
        		<td><?=$worker['city_l'.$_SESSION['lid']]?></td>    
        		<td><?=date("d.m.Y", strtotime($worker['date_end']))?></td>    
		    </tr>
        </table>
        <br /><br />
        <h3>Описание:</h3>
		<?=$worker['description_l'.$_SESSION['lid']]?>
        
        <br />
		<br />
        <a href="#order" class="button_small">Откликнуться</a>
        <?
		
	}
	else
	{

	?>
		<?=$PageContent['content'."_l$SITE_LANGUAGE_ID"]?>
        
        <br><br><br>
        
        <h1><?=$TEXT_VARS['txt_NewVacancy']?></h1>
        <br>
		<?
		$tmp = GetResume(0,"cv",0,0,0,"date_created","desc");
		foreach ($tmp as $t)
		{
			?>
			<a href="/<?=$_SESSION['l']?>/workers/<?=$t['id']?>/" class="anketa2">
				<div class="name"><?=$t['position_name']?></div><div class="zp"><?=$TEXT_VARS['txt_ZP']?>: <strong><?=$t['zp']?>$</strong>/<?=$TEXT_VARS['txt_Months']?></div><div class="edu"><?=$TEXT_VARS['txt_Education']?>: <strong><?=$t['edu_name']?></strong></div><div class="exp"><?=$TEXT_VARS['txt_Experience']?>: <?=($t['exp']*1>0?"<strong>".$t['exp']."</strong> ".$TEXT_VARS['txt_Years']:$TEXT_VARS['txt_Nope'])?></div><div class="loc"><?=$t['city']?></div>
			</a>
			<?
		}
		
	}
	?>


<div id="formOrder">
    <div class="content">
        
        <a name="order"></a><h1 align="right"><?=$TEXT_VARS['txt_AnketaSoiskatelya']?></h1>
        <br /><br /><br />


        <div id="form_end">
            <?=$TEXT_VARS['txt_CVSent']?>
            <br /><br />
            <a onclick='location.reload()' class="button_small"><?=$TEXT_VARS['txt_AddAnotherApp']?></a>
        </div>
        <div id="form_start">
            <form class="form" method="post">
                <div class="formleft">
                	<label><?=$TEXT_VARS['txt_DesiredPosition']?></label>
                    <select id="f_cv" class="select formUnit">
                    <?
						$tmp = GetPosition();
						foreach ($tmp as $t)
						{
							echo "<option value='".$t['name_l1']."'>".$t['name_l'.$_SESSION['lid']]."</option>";	
						}
					
					?>
                    </select>
                    <br />

                	<label><?=$TEXT_VARS['txt_CV']?></label>
                    <div id="uploadFileWrap">
                        <span class="btn btn-success fileinput-button">
                            <span><?=$TEXT_VARS['txt_CVFileUpload']?></span>
                            <input id='uploadFile' type='file' name='files[]'>
                        </span>
                    </div>
                    <br />

                    <label><?=$TEXT_VARS['txt_CVInfo']?></label>
                    <textarea id="f_question" wrap="soft"  class="textarea formUnit"></textarea>
                    
                </div>
                <div class="formright">
                	<label><?=$TEXT_VARS['txt_YourName']?></label>
                    <input id="f_name" type="text"  class="input important formUnit" placeholder="<?=$TEXT_VARS['txt_NamePlaceholder']?>">
                    <br />
                   	<label><?=$TEXT_VARS['txt_ContactPhone']?></label>
                    <input id="f_phone" type="text" class="input important formUnit" placeholder="<?=$TEXT_VARS['txt_Required']?>" >
					<br />
                   	<label><?=$TEXT_VARS['txt_Email']?></label>
                    <input id="f_email" type="text" class="input formUnit"  placeholder="<?=$TEXT_VARS['txt_EmailPlaceholder']?>">
                    
                    <br />
                    <label><?=$TEXT_VARS['captcha_desc']?></label>
                    <img class="captcha" src="<?=$captcha_code?>"> &nbsp; <input id='security_code' name='security_code' type='text' maxlength="3" class="important">
                </div>
		        <br class="br" />
				<br />
                <div align="center">
                    <a class="button_big submit sendButton" id="f_submit" ><?=$TEXT_VARS['txt_SendCV']?></a>
                </div>
                <input type="hidden" id="f_form" value="sendCV">
            </form>
            
        </div>                   
    </div>
</div>
