define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {
		var myResultConfig = {
			enabled: true,
			forms: {
				'form-register-refuse': {
					//成功
					success: {
						toast: '操作成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				},
			},
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-register-refuse': {
					fields: {
						note: {
							validators: {
								notEmpty: {
									message: '请输入拒绝原因',
								},
							}
						},

					},
				},
			},
		}

		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})