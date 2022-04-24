<div id="lineContent">
    <div class="content">
    	<?=$PageContent['content'."_l$SITE_LANGUAGE_ID"]?>
    </div>
</div>
<div id="lineGray"></div>
<div id="lineSlider" class="page">
    <div class="content">
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