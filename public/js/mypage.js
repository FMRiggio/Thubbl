// Place any jQuery/helper plugins in here.
$(document).ready(function() {

	if ($('.slideshow').length > 0) {
	    $('.slideshow').cycle({
			fx: 'fade'
		});
	}

	if ($('.fancybox').length > 0) {
		$('.fancybox').fancybox();
	}

	if ($('.forms.image').length > 0) {
		
		$("#background_color").placeholder()
		
		$('label > .ico').mouseover(function() {
			$(this).next().fadeIn('fast');
		});
		$('.help').mouseout(function() {
			$(this).fadeOut('fast');
		});
	}

	if ($('#flash_messages').length > 0 && !$('#flash_messages').is(':empty')) {
		$('#flash_messages').fadeOut(5000);
	}

	if ($('.delete_image_link').length > 0) {
		$('.delete_image_link').click(function(event) {
			event.preventDefault();
			var result = confirm('Sei sicuro di voler eliminare l\'immagine?');
			if (result == true) {
				top.location.href = $(this).attr('href');
			}
		});
	}
});
