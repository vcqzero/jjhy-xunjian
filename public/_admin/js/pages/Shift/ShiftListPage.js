define(
	['jquery', 'datetimepicker'],
	function($) {
		var myTimePicker = function(page) {

			var _input = page.find('#select-date')
			var timepicker = _input.datetimepicker({
				format: "YYYY-MM-D",
				locale : 'zh-cn',
			})
			var form = page.find('form.form-search-submit')
			timepicker.on('dp.change', function() {
				form.trigger('mySearch.search')
			})
		}
		return {
			init: function(pageName, page) {
				myTimePicker(page)
			}
		}
	})