var Nooges = {
	create: function(response_list, textarea_element, topic_id, parent_id, side) {
		var message_element = $('#' + textarea_element);
		var message = message_element.val();
		
		if ( 0 == message.length ) {
			alert('Please enter a message first!');
		}
		
		var data = {
			topic_id: topic_id,
			message: message,
			parent_id: parent_id,
			side: side
		};
		
		Nooges.postHtml(Nooges.buildUrl('index/create'), data, function(html) {
			message_element.val('');
			$('#' + response_list).prepend(html).show().trigger('zebra-stripe');
		});
	},
	
	vote: function(response_id, direction) {
		response_id = parseInt(response_id);
		direction = parseInt(direction);
		
		if ( 0 == response_id || true === isNaN(response_id) ) {
			alert('Please vote for an actual response');
		}
		
		if ( -1 != direction && 1 != direction ) {
			direction = 1;
		}
		
		var data = {
			response_id: response_id,
			direction: direction
		};
		
		Nooges.postHtml(Nooges.buildUrl('index/vote'), data, function(html) {
			var id = ( -1 == direction ? '#response-dislike-' + response_id : '#response-like-' + response_id );
			$(id).html(html);
		});
	},
	
	
	load: function(response_id) {
		var data = {
			response_id: response_id
		};
		
		Nooges.postHtml(Nooges.buildUrl('index/load'), data, function(html) {
			$('#response-list-children-' + response_id).html(html).show();
		});
	},
	
	hide: function(response_id) {
		$('#response-list-children-' + response_id).hide();
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