define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {
		var myResultConfig = {
			enabled: true,
			forms: {
				'form-login': {
					//成功
					success: {
						toast: '登录成功',
						route: '/',
					},

					//失败
					'error': {
						toast: '登录失败',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-login': {
					rules: {
						username: {
							required: true,
							minlength: 2
						},
						password: {
							required: true,
						},
					},
					messages: {
						username: {
							required: "请输入用户名",
						},
						password: {
							required: "请输入密码",
						},
					}
				}
			}
		}
		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})