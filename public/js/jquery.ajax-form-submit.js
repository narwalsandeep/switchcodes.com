(function($) {
	$.fn.autosubmit = function(successCallBack, errorCallback) {

		this.submit(function(event) {
			var form = $(this);
			$.ajax({
				type : form.attr('method'),
				url : form.attr('action'),
				data : form.serialize()
			}).done(function(response) {
				successCallBack(form, response);
				// Optionally alert the user of success here...
			}).fail(function() {
				errorCallback(form, response);
				// Optionally alert the user of an error here...
			});
			event.preventDefault();
		});
	}
})(jQuery)