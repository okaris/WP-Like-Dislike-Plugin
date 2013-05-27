function wpld(post_id, action) {
	var data = {
		action: 'wpld',
		post_id: post_id,
		action: action      // We pass php values differently!
	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		alert('Got this from the server: ' + response);
	});
});