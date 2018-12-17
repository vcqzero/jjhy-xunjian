define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {
		var myResultConfig = {
			enabled: true,
			forms: {
				'form-register-success': {
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
				'form-register-success': {
					fields: {
						admin_username: {
							validators: {
								notEmpty: {
									message: '请输入创建好的管理员',
								},
							}
						},

						admin_password: {
							validators: {
								notEmpty: {
									message: '请输入该管理员密码',
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