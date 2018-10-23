(function($, myCheckbox) {
	$.extend({
		'myCheckbox': myCheckbox
	})
})(jQuery, (function() {
	var EVENT_CHECKED = "checked.myCheckbox"
	
	var toggle = function(checkboxes) {
		$.each(checkboxes, function(k, v) {
			var isChecked = this['checked']
			$(this).prop('checked', !isChecked)
		});
	}
	
	var trigger = function(_switch, checkboxes) {
		var checked = checkboxes.filter(":checked")
		var length = checked.length
		_switch.trigger(EVENT_CHECKED, [checked, length])
	}
	
	return {
		init: function(_switch, checkboxes) {
			_switch.on('click', function() {
				toggle(checkboxes)
				trigger(_switch, checkboxes)
			})
			
			checkboxes.on('click', function() {
				trigger(_switch, checkboxes)
			})
			console.log('myCheckbox init success')
		},
	}
})())