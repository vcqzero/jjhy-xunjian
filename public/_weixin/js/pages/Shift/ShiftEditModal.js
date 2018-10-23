define(
	['jquery', 
	'myResult', 
	'myValidator', 
	'datetimepicker', 
	'select2'
	],
	function($, myResult, myValidator) {
		var myTimePicker = function(page) {

			var _input = page.find('#datetimepicker')
			var timepicker = _input.datetimepicker({
				format: "YYYY-MM-D",
				locale : 'zh-cn',
				useCurrent : false
			})
		}
		
		var myGuardsSelect = function(page) {

			var _select = page.find('#guard_select')
			var results = _select.attr('data-json')
			results = JSON.parse(results)
			_select.select2({
				'data' : results
			})
		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-edit-shift': {
					//成功
					success: {
						toast: '修改成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-edit-shift': {
					fields: {
						date: {
							validators: {

								notEmpty: {
									message: '请选择日期',
								},
								
								remote: {
									url: '/api/shift/validShiftType',
									type: 'POST', //以post的方式发生信息
									data: function(validator) {
										return {
											shift_type_id: validator.getFieldElements('shift_type_id').val(),
											shift_id: validator.getFieldElements('shift_id').val()
										};
									},
									message: '该日班次已安排',
								},
							}
						},
						
						shift_type_id: {
							validators: {

								notEmpty: {
									message: '请选择班次',
								},

								remote: {
									url: '/api/shift/validShiftType',
									type: 'POST', //以post的方式发生信息
									data: function(validator) {
										return {
											date: validator.getFieldElements('date').val(),
											shift_id: validator.getFieldElements('shift_id').val()
										};
									},
									message: '该日班次已安排',
								},
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
						
//						'guard_id[]': {
//							validators: {
//
//								notEmpty: {
//									message: '请设置人员安排',
//								},
//							}
//						},
						
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
						if (value) {
							return true;
						}
						alert('请选择巡逻人员')
						return false;
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