define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'auth-change-password': {
					//成功
					success: {
						toast: '修改成功,请重新登录',
						route: '/api/auth/logout',
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

					rules: {

						password: {
							required: true,
							minlength:4,
							checkPW : true,
							
						},

						password_repeat: {
							required: true,
							equalTo: 'input[name="password"]'
						},
					},

					messages: {
						password: {
							required: "请输入新密码",
							minlength:'密码至少4位',
						},
						password_repeat: {
							required: "请再次输入新密码",
							equalTo: "两次输入密码不一致",
						},
					}
				},
			},

			addMethod: function() {
				$.validator.addMethod("checkPW", function(value, element, params) {
					var checkPW = /\w/;
					return this.optional(element) || (checkPW.test(value));
				}, "密码不可包含中文");
			}
		}
		return {
			init: function(pageName, page) {
				var reason = page.find('form').attr('data-reason')
				$.alert(reason)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})