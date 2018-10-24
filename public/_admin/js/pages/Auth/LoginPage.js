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
		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
			}
		}
	})