<!--
$(document).ready(
	function()
	{ 


// top lang slide ----------------------------------------------------------------------------

		$("#topLang a").hide();
		$("#topLang").removeClass("hover");
		
		
		$("#topLang").hover(
			function(){
				$(this).addClass("hover");
				$("#topLang a").show();
				$("#topLangWrapper").hide()},
			function(){
				$(this).removeClass("hover");
				$("#topLang a").hide();
				$("#topLangWrapper").show()}
		);

// ---------------------------------------------------------------------------------------------
	
	
	
	
// index banners slider -----------------------------------------------------------------------------		

		var manualClick = false;
		$("#sliderRight").mouseup(function()
		{
			manualClick = true;
		});
		
		$("#sliderLeft").click(function()
		{
			clearInterval(swapImageInterval);
			
			var current = $("#sliderContent a.active");
			current.fadeOut(1000).removeClass('active');
			if(current.prev("a").attr("style")==null)
			{
				$("#sliderContent a").eq(parseInt($("#sliderContent a").length)-1).addClass('active').fadeIn(1000);
			}
			else
			{
				current.prev("a").addClass('active').fadeIn(1000);
			}
		});
		
		$("#sliderRight").click(function()
		{
			if (manualClick)
				clearInterval(swapImageInterval);
				
			var current = $("#sliderContent a.active");
			current.fadeOut(1000).removeClass('active');
			if(current.next("a").html()==null)
			{
				$("#sliderContent a").eq(0).addClass('active').fadeIn(1000);
			}
			else
			{
				current.next().addClass('active').fadeIn(1000);
			}
		});
		$("#sliderContent a").fadeOut(0);
		$("#sliderContent a").eq(0).addClass('active').fadeIn(1000);

		var swapImageInterval = setInterval(function()
		{
			$("#sliderRight").click();
		},3000);	

		
/*
//	Forms -----------------------------------------------------------------------------				

		$(".formUnit").blur(function()
		{
			if ($(this).val()=="")
				$(this).val($(this).attr("title"))
		}).blur();
		
		$(".formUnit").focus(function()
		{
			if ($(this).val()==$(this).attr("title"))
				$(this).val("")
			else if ($(this).val()=="")
				$(this).val($(this).attr("title"))
		});
*/		
		
// Submit check -----------------------------------------------------------------------------		

		$(".submit").live("click",function () 
		{
			var thisForm = $(this).parent().parent();
			
			var errorAmount=0;
			$(".important",thisForm).each(function() 
			{
				var element = $(this);
				var val = $.trim(element.val());
				var defaultValue = element.attr("title");
				if (!val || val==defaultValue)
				{
					element.addClass('notValid');
					errorAmount++;
				}
			});

			if (errorAmount>0)
			{
				alert($('#hiddenSubmitError').val());
				return false;
			}
			else
			{
				var name = $('#f_name',thisForm).val();
				var email = $('#f_email',thisForm).val();
				var phone = $('#f_phone',thisForm).val();
				var company = $('#f_company',thisForm).val();
				var theme = $('#f_theme',thisForm).val();
				var question = $('#f_question',thisForm).val();
				var security_code = $('#security_code',thisForm).val();
//				var f_returnPath = $('#f_returnPath',thisForm).val();

				var f_uploadedFile = $('#f_uploadedFile',thisForm).val();
				
			
				var form = $('#f_form',thisForm).val();
				
				$('#f_submit',thisForm).wrap("<div id='submitWrapper'></div>");
				
				var submitBut = $('#submitWrapper').html();
				
				$("#submitWrapper").replaceWith("<div id='loading'></div>");
				
				$.post('/cms/modules/_dbget.php',{name:name,email:email,company:company,phone:phone,question:question,security_code:security_code,form:form,f_uploadedFile:f_uploadedFile,theme:theme,operation:'sendForm'},function(data)
				{
					var tmp = $.trim(data);

					$("#loading").replaceWith(submitBut);
					
					if (tmp!="")
					{
						alert(data);
					}
					else
					{
						$("#form_start").hide();
						$("#form_end").fadeIn(500);
						
						location.hash = "#"+form+"-sent";
					}
				});
			}
		});
		$("#form_end").hide();
		$("#alertDone").hide();
		
		
		$(".important").live("change",function() 
		{
			if ($.trim($(this).val())=="")
				$(this).addClass("notValid");
			else
				$(this).removeClass("notValid");
		});		

// ------------------------------------------------------------------------


// file|image upload
	
	
	$('#uploadFile').fileupload({
		url: '/cms/modules/jquery/fileupload/server/php/',
		dataType: 'json',
		done: function (e, data) 
		{
			$.each(data.result.files, function (index, file) 
			{
				$('#uploadFileWrap').html("<input id='f_uploadedFile' type='hidden' value='"+file.name+"'>Файл загружен");
			});
		},
		add: function (e, data) 
		{
			var uploadErrors = [];
			var acceptFileTypes = /.*\.(docx?|xlsx?|pdf|odt|ods|rtf|txt|jpe?g|png)$/i;
			
			if ($.browser.msie)
			{
				data.submit();					
			}
			else
			{
				if(data.originalFiles[0]['type'].length<0 || !acceptFileTypes.test(data.originalFiles[0]['name'])) 
				{
					uploadErrors.push($('#hiddenUploadWrongFileType').val());
				}
				
				if(data.originalFiles[0]['size'].length<0 || data.originalFiles[0]['size'] > 1000000) {
					uploadErrors.push($('#hiddenUploadWrongFileSize').val());
				}
				
				if(uploadErrors.length > 0) 
				{
					alert(uploadErrors.join("<br>"));
				} else {
					data.submit();
				}		
				
			}
			
		},
		progressall: function (e, data) 
		{
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .bar').css(
				'width',
				progress + '%'
        );
    }
	});

// ------------------------------------------------------------------------
	

	}
);


$(window).load(function () 
{
	// index banners slider
	$('#slider').nivoSlider(
	{
		animSpeed: 500,
		pauseTime: 7000,
		directionNavHide:false,
		pauseOnHover: false,
		directionNav: true,
		prevText: 'prev',
        nextText: 'next'
	});

});



$(window).resize(
 	function()
	{ 
		PosElements();
	}
);


$(window).scroll(
 	function()
	{ 
		PosElements();
	}
);


function PosElements()
{

}
// -->