define(
	['jquery', 'moment', 'daterangepicker', 'myFramework'],
	function($, moment, daterangepicker, myFramework) {
		var myTimePicker = function(page) {
			var _input = page.find('#select-range-date')
			var _config = {
//				singleDatePicker: true,
				startDate: false,
				endDate: false,
				minDate : moment(),
				ranges: {
					'今天': [moment(), moment()],
//					'昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'最近7天': [moment(), moment().add(6, 'days')],
//					'最近30天': [moment().add(29, 'days'), moment()],
					'本月': [moment(), moment().endOf('month')],
//					'上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				alwaysShowCalendars: true,
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
			var _range = myFramework.route.getSearchParam('range')
			if (_range) {
				_range = _range.replace('+-+', ' - ')
				_input.val(_range)
			}else {
				_input.val('')
				$('form button').attr('disabled', 'disabled')
			}
			
		}
		
		return {
			init: function(pageName, page) {
				myTimePicker(page)

				//预加载
				requirejs(
					['select2'],
					function() {})
			}
		}
	})