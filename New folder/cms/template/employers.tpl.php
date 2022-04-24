<?
	if (!empty($worker))
	{
		?>
        <h1><?=$worker['position_name']?></h1>

        <table class='worker'>
            <tr>
        		<th>Опыт работы</th>    
        		<th>Образование</th>    
        		<th>Местоположение</th>    
        		<th>Средняя зарплата</th>    
		    </tr>
            <tr>
        		<td><?=$worker['exp']?> <?=$TEXT_VARS['txt_Years']?></td>    
        		<td><?=$worker['edu_name']?></td>    
        		<td><?=$worker['city_l'.$_SESSION['lid']]?></td>    
        		<td><?=$worker['zp']?>$/<?=$TEXT_VARS['txt_Months']?></td>    
		    </tr>
        </table>
        <br /><br />
        <div class="bigphoto"><?=PutThumbnail($worker['photo'],300,300)?></div>
        <h3>Описание:</h3>
		<?=$worker['description_l'.$_SESSION['lid']]?>
        
        <br class="br" />
		<br />
        <a href="#order" class="button_big">Заинтересованы?</a>
        <?
		
	}
	else
	{
	
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
		?>
        
		<?=$PageContent['content'."_l$SITE_LANGUAGE_ID"]?>
		
		<br><br><br>
		
		<h1><?=$TEXT_VARS['txt_BestEmployees']?></h1>
		<br>
		<?
		$tmp = GetResume(0,"resume",0,0,0,"date_created","desc");
		foreach ($tmp as $t)
		{
			?>
			<a href="/<?=$_SESSION['l']?>/employers/<?=$t['id']?>/" class="anketa">
				<div class="photo"><?=PutThumbnail($t['photo'],70,70)?></div><div class="name"><?=$t['position_name']?></div><div class="zp"><?=$TEXT_VARS['txt_ZP']?>: <strong><?=$t['zp']?>$</strong>/<?=$TEXT_VARS['txt_Months']?></div><div class="exp"><?=$TEXT_VARS['txt_Experience']?>: <?=($t['exp']*1>0?"<strong>".$t['exp']."</strong> ".$TEXT_VARS['txt_Years']:$TEXT_VARS['txt_Nope'])?></div><div class="loc"><?=$t['city']?></div>
			</a>
			<?
		}
	}
	?>

<div id="formOrder">
    <div class="content">
        
        <a name="order"></a><h1 align="right"><?=$TEXT_VARS['txt_OrderStaff']?></h1>
        <br />

        <div id="form_end">
            <?=$TEXT_VARS['txt_OrderSent']?>
            <br /><br />
            <a onclick='location.reload()' class="button_small"><?=$TEXT_VARS['txt_AddAnotherApp']?></a>
        </div>
        <div id="form_start">
            <form class="form" method="post">
                <div class="formleft">
                	<label><?=$TEXT_VARS['txt_WantToHire']?></label>
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

                	<label><?=$TEXT_VARS['txt_WithSchedule']?></label>
                    <select id="f_work" class="select formUnit">
                    	<option><?=$TEXT_VARS['txt_Schedule1']?></option>
                    	<option><?=$TEXT_VARS['txt_Schedule2']?></option>
                    	<option><?=$TEXT_VARS['txt_Schedule3']?></option>
                    </select>
                    <br />

                    <label><?=$TEXT_VARS['txt_YourRequirementsToCandidate']?></label>
                    <textarea id="f_question" wrap="soft"  class="textarea formUnit" placeholder="<?=$TEXT_VARS['txt_YourRequirementsToCandidatePlaceholder']?>"></textarea>
                    
                	<label><?=$TEXT_VARS['txt_AgeOfChild']?></label>
                    <select id="f_child" class="select formUnit">
                    	<option><?=$TEXT_VARS['txt_Age1']?></option>
                    	<option><?=$TEXT_VARS['txt_Age2']?></option>
                    	<option><?=$TEXT_VARS['txt_Age3']?></option>
                    	<option><?=$TEXT_VARS['txt_Age4']?></option>
                    </select>
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
                    
                	<label><?=$TEXT_VARS['txt_WhereFoundInformationAbout']?></label>
                    <select id="f_found" class="select formUnit">
                        <option value="я ваш постоянный клиент"><?=$TEXT_VARS['txt_WhereFound1']?></option>
                        <option value="через Facebook"><?=$TEXT_VARS['txt_WhereFound2']?></option>
                        <option value="через агентство"><?=$TEXT_VARS['txt_WhereFound3']?></option>
                        <option value="телевизионная реклама или наружная реклама"><?=$TEXT_VARS['txt_WhereFound4']?></option>
                        <option value="рекомендации знакомых"><?=$TEXT_VARS['txt_WhereFound5']?></option>
                        <option value="баннерная реклама или рассылка"><?=$TEXT_VARS['txt_WhereFound6']?></option>
					</select>
                    <br />
                    <label><?=$TEXT_VARS['captcha_desc']?></label>
                    <img class="captcha" src="<?=$captcha_code?>"> &nbsp; <input id='security_code' name='security_code' type='text' maxlength="3" class="important">
                </div>
		        <br class="br" />
				<br />
                <div align="center">
                    <a class="button_big submit sendButton" id="f_submit" ><?=$TEXT_VARS['txt_SendRequest']?></a>
                </div>
                <input type="hidden" id="f_form" value="sendOrder">
            </form>
            
        </div>                    
    </div>
</div>
