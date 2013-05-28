jQuery(document).ready(function($) {

	function wpld(post_id, wpld_action) {
		var data = {
			action: 'wpld_ajax',
			post_id: post_id,
			wpld_action: wpld_action      // We pass php values differently!
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		$.post(ajax_object.ajax_url, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	}
	function wpld_fast(post_id, wpld_action) {
		var data = {
			action: 'wpld_ajax',
			post_id: post_id,
			wpld_action: wpld_action      // We pass php values differently!
		};
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		$.post(ajax_object.blog_url + "/wp-content/plugins/wp-like-dislike/wpld-like.php", data, function(response) {
			alert('Got this from the server: ' + response);
		});
	}

	$('.wpld-like-button').click(function(){
		var post_id = $(this).data("postId");
		wpld(post_id,"like");
	});
	$('.wpld-dislike-button').click(function(){
		var post_id = $(this).data("postId");
		wpld(post_id,"dislike");
	});

});