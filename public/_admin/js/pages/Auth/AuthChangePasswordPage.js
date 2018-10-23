define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'auth-change-password': {
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
				},
			},
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'auth-change-password': {
					fields: {

						password: {
							validators: {

								notEmpty: {
									message: '请输入新密码',
								},

								stringLength: {
									message: '至少6个字符， 最长120字符',
									min: 6,
									max: 120,
								},

								regexp: {
									regexp: /\w/,
									message: "密码不可含中文字符"
								},
							}
						},

						password_repeat: {
							validators: {

								notEmpty: {
									message: '请再次输入新密码',
								},

								identical: {
									field: 'password',
									message: '两次输入密码不一致'
								}
							}
						},
					},
				},
			},
		}
		return {
			init: function(pageName, page) {
				alert('首次登陆或密码已被重置，需要重新设置密码')
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})