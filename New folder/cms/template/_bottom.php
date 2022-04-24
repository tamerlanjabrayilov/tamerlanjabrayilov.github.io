<div id="lineRed"></div>
<div id="lineFeedback">
    <div class="content">
        <br />
        <a name="order"></a>
        <br />
    	<div class="h1 red" align="center">Оставьте заявку</div>
        <br />
		<br />
        <div id="feedbackForm">

            <div id="form_end">
                <?=$TEXT_VARS['txt_OrderSent']?>
                <br /><br />
                <a onclick='location.reload()' class="button red"><?=$TEXT_VARS['txt_AddAnotherApp']?></a>
            </div>
            <div id="form_start">
                <form class="form" method="post">
                    <div class="formleft">
                        <input id="f_name" type="text"  class="input important formUnit" placeholder="<?=$TEXT_VARS['txt_NamePlaceholder']?>">
                        <br />
                        <input id="f_company" type="text"  class="input formUnit" placeholder="<?=$TEXT_VARS['txt_CompanyPlaceholder']?>">
                        <br />
                        <input id="f_phone" type="text" class="input important formUnit" placeholder="<?=$TEXT_VARS['txt_PhonePlaceholder']?>" >
                        <br />
                        <input id="f_email" type="text" class="input formUnit"  placeholder="<?=$TEXT_VARS['txt_EmailPlaceholder']?>">
                        <br />
                        
                        <div id="uploadFileWrap">
                            <label><?=$TEXT_VARS['txt_FormUpload']?></label>
                            <span class="btn btn-success fileinput-button">
	                            <span><?=$TEXT_VARS['txt_CVFileUpload']?></span>
                                <input id='uploadFile' type='file' name='files[]'>
                            </span>
                        </div>
                    </div>
                    <div class="formright">
                        <select id="f_theme" class="select formUnit">
							<option value=''>Веберите тему обращения</option>
							<option value='Предварительный заказ'>Предварительный заказ</option>
                            <option value='Системы видеонаблюдения'>Системы видеонаблюдения</option>
                            <option value='Контроль доступа'>Контроль доступа</option>
                            <option value='Охранная сигнализация'>Охранная сигнализация</option>
                            <option value='Пожарная сигнализация'>Пожарная сигнализация</option>
                            <option value='Проектирование систем'>Проектирование систем</option>
                            <option value='Системы пожаротушения'>Системы пожаротушения</option>
                        </select>
                        <br />
    
                        <textarea id="f_question" wrap="soft"  class="textarea formUnit"  placeholder="<?=$TEXT_VARS['txt_FormInfo']?>"></textarea>
                        
                        <br />
                        <label><?=$TEXT_VARS['captcha_desc']?></label>
                        <img class="captcha" src="<?=$captcha_code?>">&nbsp;&nbsp;=&nbsp;&nbsp;<input id='security_code' name='security_code' type='text' maxlength="3" class="important">
                    </div>
                    <br class="br" />
                    <br />
                    <div align="right">
                        <a class="button red submit sendButton" id="f_submit" ><?=$TEXT_VARS['txt_Send']?></a>
                    </div>
                    <input type="hidden" id="f_form" value="sendOrder">
                </form>
                
            </div>                   

        
        </div>
        <br />
		<br />
    </div>
</div>

</div>
<div id="footer">
    <div class="content">
        <div class="col1"><?=$TEXT_VARS['bottom_copyrights']?></div>
        <div class="col2">&nbsp;<?=$TEXT_VARS['social_buttons']?></div>
        <div class="col3"><a href="http://www.infoart.net.ua/" target="_blank" class="infoart" title="Разработка сайтов, дизайн сайтов">Разработка сайта</a></div>
    </div>
</div>

<input type="hidden" id="hiddenSubmitError" value="<?=$TEXT_VARS['txt_SubmitError']?>" />
<input type="hidden" id="hiddenUploadWrongFileType" value="<?=$TEXT_VARS['txt_UploadWrongFileType']?>" />
<input type="hidden" id="hiddenUploadWrongFileSize" value="<?=$TEXT_VARS['txt_UploadWrongFileSize']?>" />

<script type="text/javascript" src="/cms/modules/jquery/jquery.js"></script>
<script src="/cms/modules/jquery/fileupload/js/vendor/jquery.ui.widget.js"></script>
<script src="/cms/modules/jquery/fileupload/js/jquery.iframe-transport.js"></script>
<script src="/cms/modules/jquery/fileupload/js/jquery.fileupload.js"></script>
<link rel="stylesheet" href="/cms/modules/jquery/fileupload/jquery.fileupload.css">

<script type="text/javascript" src="/cms/template/custom.js?v=10"></script>

<link rel="stylesheet" href="/cms/modules/jquery/nivo/nivo-slider.css" type="text/css" media="screen" />
<script src="/cms/modules/jquery/nivo/jquery.nivo.slider.pack.js" type="text/javascript"></script>

</body>
</html>

<?
MySQLClose($dblink);
?>