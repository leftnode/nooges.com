var Nooges = {
	createNooge: function(response_list, textarea_element, topic_id, parent_id, side) {
		var message_element = $('#' + textarea_element);
		
		var data = {
			topic_id: topic_id,
			message: message_element.val(),
			parent_id: parent_id,
			side: side
		};
		
		Nooges.postHtml(Nooges.buildUrl('index/createnooge'), data, function(html) {
			message_element.val('');
			$('#' + response_list).prepend(html).trigger('zebra-stripe');
		});
	},
	
	
	
	
	
	postHtml: function(url, data, callback) {
		data.token = Nooges.getToken();
		$.post(url, data, callback, 'html');
	},
	
	getToken: function() {
		return $('#token').val();
	},
	
	buildUrl: function() {
		var url = 'index.php?_u=';
		var qs = '';
		if ( arguments.length > 0 ) {
			var i=1;
			var len = arguments.length;
			for ( i=0; i<len; i++) {
				qs += arguments[i];
				if ( i+1 != len ) {
					qs += '/';
				}
			}
		}
		
		url += qs;
		return url;
	}
}