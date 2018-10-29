define(
	['jquery', 
	'myResult', 
	'myValidator', 
	'select2'
	],
	function(
		$,
		myResult, 
		myValidator
	) {
		
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
				myGuardsSelect(page)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})