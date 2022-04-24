/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.language = 'ru';

	config.skin = 'kama';
	
	config.filebrowserBrowseUrl = '/cms/modules/kcfinder/browse.php?type=File';
	config.filebrowserImageBrowseUrl = '/cms/modules/kcfinder/browse.php?type=Image';
	config.filebrowserFlashBrowseUrl = '/cms/modules/kcfinder/browse.php?type=Flash';
	config.filebrowserUploadUrl = '/cms/modules/kcfinder/upload.php?type=File';
	config.filebrowserImageUploadUrl = '/cms/modules/kcfinder/upload.php?type=Image';
	config.filebrowserFlashUploadUrl = '/cms/modules/kcfinder/upload.php?type=Flash';
		 
	//config.uiColor = '#ddd';

	config.stylesSet = [
		// Блоки
		{ name : 'h1', element : 'h1'},
		{ name : 'h2', element : 'h2' },
		{ name : 'h3' , element : 'h3'},
	 
		// В тексте
		{ name : 'strong 1', element : 'strong', attributes : { 'class' : 'site_subtitle' } },
		{ name : 'strong 2', element : 'strong', attributes : { 'class' : 'site_subtitle2' } },
		{ name : 'Просто текст', element : 'div' },
		{ name : 'Рамка', element : 'div', attributes : { 'class' : 'block' }  },
	 
		// Ссылка
		{ name : 'Скачать', element : 'a', attributes : { 'class' : 'dl' } },
	 
		// Картинки
		{ name : 'Картинка слева', element : 'img', attributes : { 'style' : 'margin: 0px 30px 15px 0px; float:left;' } },
		{ name : 'Картинка справа', element : 'img', attributes : { 'style' : 'margin: 0px 0px 15px 30px; float:right;' } }
	];
	
	config.toolbar = 'Full';
/*	 
	config.toolbar_Full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['BidiLtr', 'BidiRtl'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks']
	];
*/
	config.toolbar_Full =
	[
		['Source','Preview','Maximize'],
		['Cut','Copy','Paste','PasteText','PasteFromWord', 'SpellCheck'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Styles','Font','FontSize','TextColor','BGColor']

	];	 
	config.toolbar_Basic =
	[
		['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
	];	
	
	config.extraPlugins += (config.extraPlugins ? ',aspell' : 'aspell' );
	
	//config.extraPlugins = 'geckospellchecker'; 
	
	config.scayt_autoStartup = false;
	config.disableNativeSpellChecker = false;
	config.removePlugins = 'scayt';	
};
