// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function noop() {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

if ($('body').hasClass('mypage')) {

	// Instagram Extension
	if (typeof(instagram_user_id) !== "undefined") {
		$(function() {
		    $.ajax({
		    	type: "GET",
		        dataType: "jsonp",
		        cache: false,
		        url: "https://api.instagram.com/v1/users/" + instagram_user_id + "/media/recent/?access_token=" + instagram_access_token,
		        success: function(data) {
		            for (var i = 0; i < instagram_max_photos; i++) {
		        		$(".instagram_container").append( '<div class="instagram-placeholder">'
														+ '<a href="' + data.data[i].images.standard_resolution.url + '" class="fancybox">'
														+ '<img class="instagram-image" src="' + data.data[i].images.thumbnail.url + '">'
														+ '</a>'
														+ '</div>'
														);   
		      		}     
		                            
		        }
		    });
		});
	}
}
