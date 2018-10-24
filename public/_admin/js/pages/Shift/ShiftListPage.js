define(
	['jquery', 'moment', 'daterangepicker'],
	function($, moment, daterangepicker) {
		var myTimePicker = function(page) {
			var _input = page.find('#select-range-date')
			var _config = {
				startDate: moment(),
				endDate: moment().add('6', 'days'),
				locale: {
					applyLabel: '确认',
					cancelLabel: '取消',
					fromLabel: 'From',
					toLabel: 'To',
					customRangeLabel: '自定义',
					daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
					monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
					firstDay: 1,
					//						format: 'YYYY-MM-DD hh:mm:ss',
					format: 'YYYY/MM/DD',
				}
			}
			_input.daterangepicker(_config);
		}
//		var myTimePicker = function(page) {
//
//			var _input = page.find('#select-date')
//			var timepicker = _input.datetimepicker({
//				format: "YYYY-MM-D",
//				locale : 'zh-cn',
//			})
//			
//			var form = page.find('form.form-search-submit')
//			timepicker.on('dp.change', function() {
//				form.trigger('mySearch.search')
//			})
//		}
		return {
			init: function(pageName, page) {
				myTimePicker(page)
			}
		}
	})