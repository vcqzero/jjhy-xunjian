define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {
		var myResultConfig = {
			enabled: true,
			forms: {
				'form-register': {
					//成功
					success: {
						callback : function() {
							$.toptip('提交成功', 'success');
							var openid = location.search;
							location = '/register' + openid
						},
					},

					//失败
					'error': {
						toast: '发生错误',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-register': {
					rules: {
						workyard_name: {
							required: true,
						},
						workayrd_address: {
							required: true,
						},
						admin_realname: {
							required: true,
						},
						admin_tel: {
							required: true,
							tel: true,
						},
					},
					messages: {
						workyard_name: {
							required: "请输入工地名称",
						},
						workayrd_address: {
							required: "请输入工地地址",
						},
						admin_realname: {
							required: "请输入联系人姓名",
						},
						admin_tel: {
							required: "请输入联系人电话",
							tel: '请输入正确手机号'
						},
					},
				}
			},
			addMethod: function() {
				jQuery.validator.addMethod("tel", function(value, element) {
					var tel = /^[1][3,4,5,6,7,8][0-9]{9}$/;
					return this.optional(element) || (tel.test(value));
				}, "请输入正确手机号码");
			}
		}
		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})