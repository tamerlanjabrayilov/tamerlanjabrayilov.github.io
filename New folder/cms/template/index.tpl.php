<div id="lineSlider">
    <div class="content">
    	<div class="buttonsTop">
        	<?=$TEXT_VARS['top_phones']?>
        </div>
        <div id="slider" class="nivoSlider">
        <?
            $banners = GetBanners(0,true);
            foreach ($banners as $banner)
            {
                echo  "<a href='".$banner['url']."'><img src='".$banner['image']."'></a>";
            }
            
        ?>
        </div>
    </div>
</div>
<div id="lineBlue"></div>
<div id="lineContent">
    <div class="content">
    	<?=$PageContent['content'."_l$SITE_LANGUAGE_ID"]?>
    </div>
</div>
<!--
<div id="lineClients">
    <div class="content">
        <br />
        <br />
    	<div class="h1 black" align="center">Клиенты</div>
        <br />
		<br />
    </div>
</div>
-->
