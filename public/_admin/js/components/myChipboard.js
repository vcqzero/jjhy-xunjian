(function($, myChipboard) {
	$.extend({
		'myChipboard': myChipboard
	})
})(jQuery, (function() {
	return {
		init: function() {
			var chipboard = new ClipboardJS('[data-clipboard="true"]')

			chipboard.on('success', function(e) {
				var _text = e.text
				e.clearSelection();
				$.myPnotify.success('已复制到剪切板')
				console.log('已复制到剪切板  ' + _text)
			})

			chipboard.on('error', function(e) {
				console.error('Action:', e.action);
				console.error('Trigger:', e.trigger);
			})
			
			console.log('myChipboard init success')
		},
	}
})())