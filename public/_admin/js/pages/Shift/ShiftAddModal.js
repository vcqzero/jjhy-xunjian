define(
	['jquery', 
	'myResult', 
	'myValidator', 
	'moment', 
	'daterangepicker',
	'select2',
	'select2_zh_cn'
	],
	function(
		$,
		myResult, 
		myValidator, 
		moment, 
		daterangepicker) {
		var myTimePicker = function(page) {

			var _input = page.find('#datetimepicker')
			var _config = {
				singleDatePicker: false,
				startDate: moment(),
				endDate: moment(),
				minDate: moment(),
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
		
		var myGuardsSelect = function(page) {

			var _select = page.find('#guard_select')
			var results = _select.attr('data-json')
			results = JSON.parse(results)
			_select.select2({
				language:'zh-CN',
				placeholder: '请选择巡逻员',
				'data' : results
			})
		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-add-shift': {
					//成功
					success: {
						toast: '添加成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '添加失败，请检查班次是否已存在',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-add-shift': {
					fields: {
						date: {
							validators: {

								notEmpty: {
									message: '请选择日期',
								},
								
							}
						},
						
						shift_type_id: {
							validators: {

								notEmpty: {
									message: '请选择班次',
								},
								
								stringLength: {
									message: '请选择班次',
									min: 1,
								},
//
							}
						},
						
						times: {
							validators: {
								notEmpty: {
									message: '请输入巡逻圈数',
								},
								
								integer: {
									message: '请输入数字',
								},
							}
						},
						
						note: {
							validators: {
								stringLength: {
									message: '最长160个字符',
									max: 512,
								},
							}
						},
					},
					
					callback : function() {
						var form = arguments[0]
						var _select_guard = form.find('select[name="guard_ids[]"]')
						var value = _select_guard.val()
						if (value == null) {
							alert('请选择巡逻人员')
							return false;
						}
						var point_name = $('#point-name')
						if (point_name.length < 1){
							alert('请先增加巡逻点')
							return false;
						}
						return true;
					}
				},
			},

		}

		return {
			init: function(pageName, page) {
				myTimePicker(page)
				myGuardsSelect(page)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})