// Place any jQuery/helper plugins in here.
$(document).ready(function() {

	if ($('.slideshow').length > 0) {
	    $('.slideshow').cycle({
			fx: 'fade',
			delay: -2000
		});
	}

	// Instagram Extension
	if ($('.instagram_container').length > 0) {

		$(".instagram_container").instagram({
			hash: client_hashtag,
			show: '9',
			clientId: client_id
		});

	}


	if ($('.previews').length > 0) {

		$(window).bind('resize, load', function() {
			var windowW = $(window).width();
			if (windowW > 1343) {
				$('.previews img').css('left', ((windowW - 1343)/2) + 'px');
			} else {
				$('.previews img').css('left', '-' + ((1343 - windowW)/2) + 'px');
			}
		});

	    $('.previews').cycle({
			fx: 'fade',
			speed: 700,
			timeout: 700
		});
	}
	
	/*$(window).bind('scroll', function() {
		var height = Math.round(($(window).width() * 625) / 1343) + $('header').height();
	})*/

	if ($('.menu').length > 0) {
		$('.menu a, .box a').click(function(event) {
			event.preventDefault();
			$('.menu a').removeClass('active');
			$(this).addClass('active');

			var url = $(this).attr('href');
			hashname = url.substr(url.indexOf('#')+1);
			if ($('#' + hashname).length > 0) {
				$('html, body').animate({scrollTop: $('#' + hashname).offset().top }, 'slow');
			} else {
				location.href = url;
			}
			
		});
	}
});
