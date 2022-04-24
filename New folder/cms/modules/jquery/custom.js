$(document).ready(
	function()
	{ 
		$("a[rel*=lightbox]").lightBox();
		$(document).pngFix(); 
		
		$("a > strong[class*=site_subtitle]").parent().attr("class","site_subtitle");
		
		var totalProfi = 7;
		var width = totalProfi*36;
		
		$(":radio,:checkbox").css({border:'none'});

		$("#order").change(function () 
		{
			$("input:not(:radio,:checkbox)").focus(function()
			{
				$(this).css({backgroundColor:'#FFFF33'});
			}).blur(function()
			{
				$(this).css({backgroundColor:'#fff'});
			});

			if ($("select option:selected[id^=nan]").val()!=null)
				$("#childage").show();
			else
			{
				$("#childage").hide();
				$("#childage > input").val("");
			}
			
		}).change();
		
		$('#right').click(function() 
		{
			var left = parseInt($('#images').css("margin-left"));
			if ((left+width)>180)
				$('#images').animate({marginLeft: left-36+'px'}, 100);
		});		
		$('#left').click(function() 
		{
			var left = parseInt($('#images').css("margin-left"));
			if (left<0)
				$('#images').animate({marginLeft: left+36+'px'}, 100);
		});		
		
		$('.img').click(function() 
		{
			$("#figure").stop(true,true);
			$("#description").stop(true,true);
			var imageName = $(this).attr("src").slice(0,-3)+"jpg";	
			var imageDesc = $(this).attr("title");	
			var imageUrl = $(this).attr("alt");	
			$("#figure").attr('href', '/'+imageUrl+'/');	
			$("#description").attr('href', '/'+imageUrl+'/');	
			$("#figure").attr('title', ''+imageDesc+'');	
//			$("#orderBut").attr('href', '/order/');	
			$("#figure").fadeOut(0).css('backgroundImage', 'url('+imageName+')').fadeIn(1000);	
			$("#description").fadeOut(0).html(imageDesc).fadeIn(0);	
		});

		$('.img:first').click();
	}
);