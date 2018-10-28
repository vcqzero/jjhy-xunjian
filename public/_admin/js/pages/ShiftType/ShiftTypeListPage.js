define(
	['jquery'],
	function($) {

		return {
			init: function(pageName, page) {
				requirejs(['datetimepicker'], function() {})
			}
		}
	})